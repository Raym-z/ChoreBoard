<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Invitation;

class HouseholdController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $household = $user->households()->find(session('current_household_id')) ?? $user->households()->first();
        $members = $household ? $household->users()->get() : collect();
        $pendingInvitations = $household ? $household->invitations()->where('status', 'pending')->get() : collect();
        // Incoming invites for this user (by email)
        $incomingInvites = \App\Models\Invitation::where('email', $user->email)->where('status', 'pending')->get();
        Log::info('HouseholdController@show: members', $members->map(fn($m) => [$m->id, $m->name, $m->pivot->role])->toArray());
        return view('household.show', compact('household', 'members', 'pendingInvitations', 'incomingInvites'));
    }

    /**
     * Show the household management page (list, switch, create)
     */
    public function manage()
    {
        $user = Auth::user();
        $households = $user->households;
        $currentHouseholdId = session('current_household_id', $households->first()?->id);
        return view('household.manage', compact('households', 'currentHouseholdId'));
    }

    /**
     * Switch the current household (store in session)
     */
    public function switch($id)
    {
        $user = Auth::user();
        if (!$user->households->contains($id)) {
            abort(403, 'Not a member of this household');
        }
        session(['current_household_id' => $id]);
        return redirect()->back()->with('success', 'Switched household!');
    }

    /**
     * Show create household form
     */
    public function create()
    {
        return view('household.create');
    }

    /**
     * Store a new household and join it
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $household = \App\Models\Household::create([
            'name' => $request->name,
        ]);
        $user = Auth::user();
        $user->households()->attach($household->id, ['role' => 'admin']);
        session(['current_household_id' => $household->id]);
        return redirect()->route('household.manage')->with('success', 'Household created and joined!');
    }

    /**
     * Promote a member to admin in the current household
     */
    public function promote($userId)
    {
        $user = Auth::user();
        $householdId = session('current_household_id');
        $pivot = $user->households()->where('household_id', $householdId)->first()?->pivot;
        if (!$pivot || $pivot->role !== 'admin') {
            abort(403, 'Only admins can promote members.');
        }
        $target = \App\Models\User::findOrFail($userId);
        $target->households()->updateExistingPivot($householdId, ['role' => 'admin']);
        Log::info('Promoted user to admin', ['user_id' => $userId, 'household_id' => $householdId]);
        $updatedRole = $target->households()->where('household_id', $householdId)->first()?->pivot->role;
        Log::info('Updated role after promote', ['user_id' => $userId, 'role' => $updatedRole]);
        return back()->with('success', 'User promoted to admin.');
    }

    /**
     * Demote an admin to member in the current household
     */
    public function demote($userId)
    {
        $user = Auth::user();
        $householdId = session('current_household_id');
        $pivot = $user->households()->where('household_id', $householdId)->first()?->pivot;
        if (!$pivot || $pivot->role !== 'admin') {
            abort(403, 'Only admins can demote admins.');
        }
        $target = \App\Models\User::findOrFail($userId);
        // Prevent demoting self if only one admin remains
        $adminCount = \App\Models\Household::find($householdId)->users()->wherePivot('role', 'admin')->count();
        if ($target->id == $user->id && $adminCount <= 1) {
            return back()->with('error', 'You cannot demote yourself as the only admin.');
        }
        $target->households()->updateExistingPivot($householdId, ['role' => 'member']);
        Log::info('Demoted user to member', ['user_id' => $userId, 'household_id' => $householdId]);
        $updatedRole = $target->households()->where('household_id', $householdId)->first()?->pivot->role;
        Log::info('Updated role after demote', ['user_id' => $userId, 'role' => $updatedRole]);
        return back()->with('success', 'User demoted to member.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\HouseholdInviteMail;

class InvitationController extends Controller
{
    // Admin: Show/copy invite code for their household
    public function showInvite()
    {
        $user = Auth::user();
        $household = $user->households()->first();
        if (!$household) {
            abort(403, 'No household found.');
        }
        // Generate invite code if missing
        if (!$household->invite_code) {
            $household->invite_code = strtoupper(Str::random(8));
            $household->save();
        }
        $inviteLink = route('invitations.joinForm', ['code' => $household->invite_code]);
        $pendingInvitations = $household->invitations()->where('status', 'pending')->get();
        return view('invitations.show', compact('household', 'inviteLink', 'pendingInvitations'));
    }

    // Revoke/cancel an invitation
    public function revoke($id)
    {
        $invite = Invitation::findOrFail($id);
        $user = Auth::user();
        if ($invite->household_id !== $user->households()->first()->id) {
            abort(403, 'Unauthorized');
        }
        $invite->status = 'revoked';
        $invite->revoked_at = now();
        $invite->save();
        return back()->with('success', 'Invitation revoked.');
    }

    // Show join form for invite code
    public function joinForm($code = null)
    {
        return view('invitations.join', ['code' => $code]);
    }

    // Process join by code (mark invitation as accepted if exists)
    public function join(Request $request)
    {
        $request->validate(['code' => 'required|string|exists:households,invite_code']);
        $household = Household::where('invite_code', $request->code)->firstOrFail();
        $user = Auth::user();
        // Attach user to household if not already a member
        if (!$user->households->contains($household->id)) {
            $user->households()->attach($household->id);
        }
        // Mark invitation as accepted if exists for this email/household/code
        $invite = Invitation::where('household_id', $household->id)
            ->where('code', $request->code)
            ->where('email', $user->email)
            ->where('status', 'pending')
            ->first();
        if ($invite) {
            $invite->status = 'accepted';
            $invite->accepted_at = now();
            $invite->save();
        }
        return redirect()->route('dashboard')->with('success', 'You have joined the household!');
    }

    // (Optional) Admin: Send email invite
    public function sendInvite(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = Auth::user();
        $household = $user->households()->first();
        if (!$household) {
            abort(403, 'No household found.');
        }
        // Generate invite code
        $code = strtoupper(Str::random(10));
        $invite = Invitation::create([
            'household_id' => $household->id,
            'email' => $request->email,
            'code' => $code,
            'status' => 'pending',
        ]);
        $inviteLink = route('invitations.joinForm', ['code' => $code]);
        // Send email
        Mail::to($request->email)->send(new HouseholdInviteMail($household, $inviteLink));
        return back()->with('success', 'Invitation sent to ' . $request->email . '!');
    }
}

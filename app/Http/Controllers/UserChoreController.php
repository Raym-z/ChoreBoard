<?php

namespace App\Http\Controllers;

use App\Models\UserChore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserChoreController extends Controller
{
    public function update(Request $request, UserChore $userChore)
    {
        // Bulk completion
        if ($request->has('bulk') && $request->has('ids')) {
            $ids = explode(',', $request->ids);
            $count = 0;
            foreach ($ids as $id) {
                $chore = UserChore::where('id', $id)->where('user_id', Auth::id())->where('status', 'pending')->first();
                if ($chore) {
                    $chore->status = 'completed';
                    $chore->completed_at = now();
                    $chore->save();
                    $count++;
                }
            }
            return redirect()->back()->with('success', "$count chores marked as complete!");
        }
        // Only allow the owner to mark as complete
        if ($userChore->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        if ($request->status === 'completed' && $userChore->status !== 'completed') {
            $userChore->markAsCompleted();
        }
        return redirect()->back()->with('success', 'Chore marked as complete!');
    }
}
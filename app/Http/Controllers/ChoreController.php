<?php

namespace App\Http\Controllers;

use App\Models\Chore;
use App\Models\User;
use App\Models\UserChore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreChoreRequest;
use App\Http\Requests\UpdateChoreRequest;

class ChoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Chore::with('creator');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $chores = $query->latest()->paginate(10)->withQueryString();
        return view('chores.index', compact('chores'));
    }

    public function create()
    {
        $users = User::all();
        return view('chores.create', compact('users'));
    }

    public function store(StoreChoreRequest $request)
    {
        // Default due_date to today at 23:59 if not provided
        $dueDate = $request->due_date;
        if (empty($dueDate)) {
            $dueDate = now()->setTime(23, 59, 0)->toDateString();
        }
        $chore = Chore::create([
            'name' => $request->name,
            'description' => $request->description,
            'points' => $request->points,
            'frequency' => $request->frequency,
            'priority' => $request->priority,
            'created_by' => Auth::id(),
        ]);

        // Assign to users
        foreach ($request->assigned_users as $userId) {
            UserChore::create([
                'chore_id' => $chore->id,
                'user_id' => $userId,
                'due_date' => $dueDate,
                'next_due_date' => $dueDate,
                'is_recurring' => $request->frequency !== 'one-time',
                'status' => 'pending',
            ]);
        }

        return redirect()->route('chores.index')->with('success', 'Chore created and assigned!');
    }

    public function edit(Chore $chore)
    {
        $users = User::all();
        return view('chores.edit', compact('chore', 'users'));
    }

    public function update(UpdateChoreRequest $request, Chore $chore)
    {
        $chore->update($request->only(['name', 'description', 'points', 'frequency', 'priority']));
        // Optionally update assignments here
        return redirect()->route('chores.index')->with('success', 'Chore updated!');
    }

    public function destroy(Chore $chore)
    {
        $chore->delete();
        return redirect()->route('chores.index')->with('success', 'Chore deleted!');
    }
}
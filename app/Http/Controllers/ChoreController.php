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
          /**
            * @method void middleware($middleware, array $options = [])
            */
        $this->middleware('admin');
    }

    public function index()
    {
        $chores = Chore::with('creator')->latest()->paginate(10);
        return view('chores.index', compact('chores'));
    }

    public function create()
    {
        $users = User::all();
        return view('chores.create', compact('users'));
    }

    public function store(StoreChoreRequest $request)
    {
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
                'due_date' => $request->due_date,
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
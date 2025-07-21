@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Create Chore</h2>
    <form action="{{ route('chores.store') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="points" class="form-label">Points</label>
            <input type="number" name="points" id="points" class="form-control" value="{{ old('points', 1) }}" min="1"
                required>
            @error('points') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="frequency" class="form-label">Frequency</label>
            <select name="frequency" id="frequency" class="form-select" required>
                <option value="one-time" {{ old('frequency') == 'one-time' ? 'selected' : '' }}>One-time</option>
                <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
            </select>
            @error('frequency') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            <select name="priority" id="priority" class="form-select" required>
                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
            </select>
            @error('priority') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date') }}"
                required>
            @error('due_date') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="assigned_users" class="form-label">Assign to Users</label>
            <select name="assigned_users[]" id="assigned_users" class="form-select" multiple required>
                @foreach($users as $user)
                <option value="{{ $user->id }}"
                    {{ (collect(old('assigned_users'))->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }}
                </option>
                @endforeach
            </select>
            @error('assigned_users') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create Chore</button>
        <a href="{{ route('chores.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
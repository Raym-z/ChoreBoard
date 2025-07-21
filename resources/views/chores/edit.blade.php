@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Chore</h2>
    <form action="{{ route('chores.update', $chore) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $chore->name) }}"
                required>
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description"
                class="form-control">{{ old('description', $chore->description) }}</textarea>
            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="points" class="form-label">Points</label>
            <input type="number" name="points" id="points" class="form-control"
                value="{{ old('points', $chore->points) }}" min="1" required>
            @error('points') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="frequency" class="form-label">Frequency</label>
            <select name="frequency" id="frequency" class="form-select" required>
                <option value="one-time" {{ old('frequency', $chore->frequency) == 'one-time' ? 'selected' : '' }}>
                    One-time</option>
                <option value="daily" {{ old('frequency', $chore->frequency) == 'daily' ? 'selected' : '' }}>Daily
                </option>
                <option value="weekly" {{ old('frequency', $chore->frequency) == 'weekly' ? 'selected' : '' }}>Weekly
                </option>
                <option value="monthly" {{ old('frequency', $chore->frequency) == 'monthly' ? 'selected' : '' }}>Monthly
                </option>
            </select>
            @error('frequency') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            <select name="priority" id="priority" class="form-select" required>
                <option value="low" {{ old('priority', $chore->priority) == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority', $chore->priority) == 'medium' ? 'selected' : '' }}>Medium
                </option>
                <option value="high" {{ old('priority', $chore->priority) == 'high' ? 'selected' : '' }}>High</option>
            </select>
            @error('priority') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update Chore</button>
        <a href="{{ route('chores.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
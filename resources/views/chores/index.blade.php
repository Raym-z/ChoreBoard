@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Chores</h2>
        <a href="{{ route('chores.create') }}" class="btn btn-success">Create Chore</a>
    </div>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Points</th>
                        <th>Frequency</th>
                        <th>Priority</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chores as $chore)
                    <tr>
                        <td>{{ $chore->name }}</td>
                        <td>{{ $chore->description }}</td>
                        <td>{{ $chore->points }}</td>
                        <td>{{ ucfirst($chore->frequency) }}</td>
                        <td>{{ ucfirst($chore->priority) }}</td>
                        <td>{{ $chore->creator->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('chores.edit', $chore) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('chores.destroy', $chore) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Delete this chore?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $chores->links() }}
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Manage Households</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card mb-4">
        <div class="card-header">Your Households</div>
        <div class="card-body">
            @if($households->count())
                <ul class="list-group mb-3">
                    @foreach($households as $household)
                        <li class="list-group-item d-flex justify-content-between align-items-center {{ $household->id == $currentHouseholdId ? 'active' : '' }}">
                            <span>{{ $household->name }}</span>
                            @if($household->id == $currentHouseholdId)
                                <span class="badge bg-success">Current</span>
                            @else
                                <form method="POST" action="{{ route('household.switch', $household->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Switch</button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-muted">You are not a member of any households.</div>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header">Create New Household</div>
        <div class="card-body">
            <form method="POST" action="{{ route('household.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Household Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Household</button>
            </form>
        </div>
    </div>
</div>
@endsection 
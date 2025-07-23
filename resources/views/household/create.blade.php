@extends('layouts.app')

@section('content')
<div class="container py-4 d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <h2 class="mb-3 text-center">Create a New Household</h2>
            <form method="POST" action="{{ route('household.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Household Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Create Household</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 
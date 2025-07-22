@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Join a Household</h2>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('invitations.join') }}">
                @csrf
                <div class="mb-3">
                    <label for="code" class="form-label">Invite Code</label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $code) }}"
                        required>
                    @error('code') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary">Join Household</button>
            </form>
        </div>
    </div>
</div>
@endsection
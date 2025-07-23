@extends('layouts.app')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <h2 class="mb-3 text-center" style="color: #2d3a3a;">Sign Up for ChoreBoard</h2>
            <p class="text-center text-muted mb-4">Create your account and start tracking, assigning, and completing
                household chores!</p>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required
                        autofocus autocomplete="name">
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="username">
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required
                        autocomplete="new-password">
                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation"
                        required autocomplete="new-password">
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </div>
                <div class="text-center">
                    <a class="small" href="{{ route('login') }}">Already have an account? Log in</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
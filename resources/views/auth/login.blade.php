@extends('layouts.app')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <h2 class="mb-3 text-center" style="color: #2d3a3a;">ChoreBoard Login</h2>
            <p class="text-center text-muted mb-4">Welcome back! Log in to track and complete your household chores.</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required
                        autofocus autocomplete="username">
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required
                        autocomplete="current-password">
                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Log In</button>
                </div>
                <div class="d-flex justify-content-between">
                    @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">Forgot your password?</a>
                    @endif
                    <a class="small" href="{{ route('register') }}">Sign up</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
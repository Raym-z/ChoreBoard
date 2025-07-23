@extends('layouts.app')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <h2 class="mb-3 text-center" style="color: #2d3a3a;">Confirm Your Password</h2>
            <p class="text-center text-muted mb-4">For your security, please confirm your password to continue.</p>
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Confirm Password</button>
                </div>
                <div class="text-center">
                    <a class="small" href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

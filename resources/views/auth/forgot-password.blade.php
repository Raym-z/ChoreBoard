@extends('layouts.app')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <h2 class="mb-3 text-center" style="color: #2d3a3a;">Forgot Your Password?</h2>
            <p class="text-center text-muted mb-4">Enter your email and we'll send you a link to reset your password.</p>
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
                </div>
                <div class="text-center">
                    <a class="small" href="{{ route('login') }}">Back to login</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

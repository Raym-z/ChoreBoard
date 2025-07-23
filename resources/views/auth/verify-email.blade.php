@extends('layouts.app')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <h2 class="mb-3 text-center" style="color: #2d3a3a;">Verify Your Email</h2>
            <p class="text-center text-muted mb-4">Thanks for signing up! Please verify your email address by clicking the link we just emailed to you. If you didn't receive the email, we can send another.</p>
            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success mb-3">
                    A new verification link has been sent to your email address.
                </div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Resend Verification Email</button>
                </div>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <div class="d-grid">
                    <button type="submit" class="btn btn-link">Log Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

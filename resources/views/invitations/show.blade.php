@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Household Invitation</h2>
    <div class="card mb-4">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Invite Code</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="inviteCode" value="{{ $household->invite_code }}"
                        readonly>
                    <button class="btn btn-outline-secondary" type="button"
                        onclick="navigator.clipboard.writeText('{{ $household->invite_code }}')">Copy</button>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Invite Link</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="inviteLink" value="{{ $inviteLink }}" readonly>
                    <button class="btn btn-outline-secondary" type="button"
                        onclick="navigator.clipboard.writeText('{{ $inviteLink }}')">Copy</button>
                </div>
            </div>
            <form method="POST" action="{{ route('invitations.sendInvite') }}" class="row g-2 align-items-end">
                @csrf
                <div class="col-auto">
                    <label for="inviteEmail" class="form-label mb-0">Send Email Invite</label>
                    <input type="email" name="email" id="inviteEmail" class="form-control" placeholder="user@email.com">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Send Invite</button>
                </div>
            </form>
            @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    @if(isset($pendingInvitations) && count($pendingInvitations))
    <div class="card">
        <div class="card-header">Pending Invitations</div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach($pendingInvitations as $invite)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $invite->email ?? 'Direct link/code' }}
                    <span class="badge bg-warning text-dark">Pending</span>
                    <form method="POST" action="{{ route('invitations.revoke', $invite->id) }}" class="d-inline ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Revoke</button>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
@endsection
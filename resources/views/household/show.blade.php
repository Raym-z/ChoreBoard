@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>My Households</h2>
    @php
    $user = Auth::user();
    $households = $user->households;
    $currentHouseholdId = session('current_household_id', $households->first()?->id);
    $currentHousehold = $households->firstWhere('id', $currentHouseholdId);
    @endphp
    <div class="mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Your Households</span>
                <a href="{{ route('household.manage') }}" class="btn btn-sm btn-outline-primary">Manage/Create</a>
            </div>
            <div class="card-body">
                @if($households->count())
                <ul class="list-group mb-3">
                    @foreach($households as $household)
                    <li
                        class="list-group-item d-flex justify-content-between align-items-center {{ $household->id == $currentHouseholdId ? 'active' : '' }}">
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
    </div>
    @if($currentHousehold)
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ $currentHousehold->name }}</h5>
            <div class="mb-2">
                <strong>Members:</strong>
                <ul class="list-inline mb-0">
                    @foreach($members as $member)
                    <li class="list-inline-item">
                        {{ $member->name }} <span class="text-muted">({{ $member->email }})</span>
                        @php $role = $member->pivot->role ?? 'member'; @endphp
                        <span
                            class="badge bg-{{ $role === 'admin' ? 'primary' : 'secondary' }} ms-1">{{ ucfirst($role) }}</span>
                        @if($user->households->find($currentHouseholdId)?->pivot->role === 'admin' && $member->id !==
                        $user->id)
                        @if($role === 'member')
                        <form method="POST" action="{{ route('household.promote', $member->id) }}"
                            class="d-inline ms-1">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">Promote</button>
                        </form>
                        @elseif($role === 'admin')
                        <form method="POST" action="{{ route('household.demote', $member->id) }}" class="d-inline ms-1">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning">Demote</button>
                        </form>
                        @endif
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            <a href="{{ route('invitations.showInvite') }}" class="btn btn-primary">Invite Users</a>
        </div>
    </div>
    @if($pendingInvitations->count())
    <div class="card mb-4">
        <div class="card-header">Pending Invitations</div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach($pendingInvitations as $invite)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $invite->email ?? 'Direct link/code' }}
                    <span class="badge bg-warning text-dark">Pending</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    @endif
    @if($incomingInvites->count())
    <div class="card mb-4">
        <div class="card-header">Your Incoming Invitations</div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach($incomingInvites as $invite)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        From household: <strong>{{ $invite->household->name ?? 'Unknown' }}</strong><br>
                        Code: <code>{{ $invite->code }}</code>
                    </span>
                    <a href="{{ route('invitations.joinForm', ['code' => $invite->code]) }}"
                        class="btn btn-success btn-sm">Accept</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
@endsection
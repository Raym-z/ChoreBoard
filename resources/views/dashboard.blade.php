@extends('layouts.app')

@section('content')
@php
$user = Auth::user();
$households = $user->households;
$currentHouseholdId = session('current_household_id', $households->first()?->id);
$currentHousehold = $households->firstWhere('id', $currentHouseholdId);
@endphp
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-3">Welcome to ChoreBoard!</h1>
                <p class="lead">Track, assign, and complete household chores. Compete for the top spot on the
                    leaderboard!</p>
                <div class="mb-2">
                    <span class="fw-bold">Active Household:</span>
                    <span class="badge bg-info text-dark">{{ $currentHousehold?->name ?? 'None' }}</span>
                    <a href="{{ route('household.manage') }}" class="btn btn-sm btn-outline-primary ms-2">Switch
                        Household</a>
                </div>
            </div>
            @if($isAdmin)
            <a href="{{ route('chores.index') }}" class="btn btn-primary">Manage Chores</a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card mb-4">
                <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between bg-light border-0"
                    style="min-height: 56px;">
                    <div class="d-flex align-items-center mb-2 mb-md-0 gap-2">
                        <div class="d-flex align-items-center">
                            <a href="?period={{ $period }}&date={{ $prevDate }}"
                                class="btn btn-outline-secondary btn-sm px-2 py-1 me-1"
                                style="border-radius: 0.375rem 0 0 0.375rem; font-size: 1rem;">&lt;</a>
                            <div class="btn-group" role="group" aria-label="Period filter">
                                <a href="?period=today"
                                    class="btn btn-outline-primary btn-sm px-3 py-1 {{ $isToday ? 'active' : '' }}"
                                    style="border-radius: 0; font-size: 1rem;">Today</a>
                                <a href="?period=week"
                                    class="btn btn-outline-primary btn-sm px-3 py-1 {{ $isThisWeek ? 'active' : '' }}"
                                    style="border-radius: 0; font-size: 1rem;">This Week</a>
                                <a href="?period=month"
                                    class="btn btn-outline-primary btn-sm px-3 py-1 {{ $isThisMonth ? 'active' : '' }}"
                                    style="border-radius: 0; font-size: 1rem;">This Month</a>
                            </div>
                            <a href="?period={{ $period }}&date={{ $nextDate }}"
                                class="btn btn-outline-secondary btn-sm px-2 py-1 ms-1"
                                style="border-radius: 0 0.375rem 0.375rem 0; font-size: 1rem;">&gt;</a>
                        </div>
                    </div>
                    <div class="text-end flex-grow-1 d-flex align-items-center justify-content-end"
                        style="min-height: 40px;">
                        @if($period == 'today') Your Chores for {{ $baseDate->toFormattedDateString() }}
                        @elseif($period == 'week') Your Chores for the Week of
                        {{ $baseDate->startOfWeek()->toFormattedDateString() }}
                        @elseif($period == 'month') Your Chores for {{ $baseDate->format('F Y') }}
                        @endif
                    </div>
                </div>
                <div class="card-body" style="min-height: 80px;">
                    @if($chores->count())
                    <div class="overflow-auto" style="max-height: 350px; min-height: 100px;">
                        <ul class="list-group">
                            @foreach($chores as $userChore)
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between"
                                    style="min-height: 48px;">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <span
                                            class="badge px-3 py-2 me-2 bg-{{ $userChore->overdue ?? false ? 'danger' : ($userChore->status === 'completed' ? 'success' : 'secondary') }}"
                                            style="min-width: 100px; text-align: center;">
                                            {{ $userChore->overdue ?? false ? 'Overdue' : ucfirst($userChore->status) }}
                                        </span>
                                        @if($userChore->status === 'pending' || ($userChore->overdue ?? false))
                                        <span class="badge bg-light text-dark border me-2"
                                            style="font-size: 0.85em; font-weight: normal;">{{ $userChore->due_label ?? '' }}</span>
                                        @endif
                                        <span>
                                            {{ $userChore->chore->name ?? 'Chore' }}
                                            @if($userChore->is_recurring && $userChore->chore->frequency !== 'one-time')
                                            <i class="fas fa-redo-alt text-muted ms-1" title="Recurring chore"></i>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="ms-2 d-flex align-items-center">
                                        @if($userChore->status === 'pending' || ($userChore->overdue ?? false) ||
                                        $isAdmin)
                                        <!-- Show the dropdown only if there are actions available -->
                                        <div class="dropdown">
                                            <button class="btn btn-link text-dark p-0 d-flex align-items-center"
                                                type="button" id="dropdownMenuButton{{ $userChore->id }}"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <x-icons.ellipsis-vertical width="20" height="20" class="text-dark" />
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuButton{{ $userChore->id }}">
                                                @if($userChore->status === 'pending')
                                                <li>
                                                    <form action="{{ route('user-chores.update', $userChore) }}"
                                                        method="POST" class="d-inline ajax-complete-chore">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button class="dropdown-item" type="submit">Mark
                                                            Complete</button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($isAdmin)
                                                <li><a class="dropdown-item"
                                                        href="{{ route('chores.edit', $userChore->chore) }}">Edit</a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('chores.destroy', $userChore->chore) }}"
                                                        method="POST" onsubmit="return confirm('Delete this chore?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="dropdown-item text-danger"
                                                            type="submit">Delete</button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <p>No chores found for this period!</p>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header">This Week's Progress</div>
                <div class="card-body">
                    <div class="mb-2">Points earned: <strong>{{ $pointsThisWeek }}</strong></div>
                    <div class="progress mb-2" style="height: 24px; position: relative;">
                        <div class="progress-bar d-flex align-items-center justify-content-center" role="progressbar"
                            style="width: {{ $progressPercent }}%; font-weight: bold; font-size: 1rem;"
                            aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $progressPercent }}%
                        </div>
                    </div>
                    <small>{{ $progressPercent }}% of your chores completed this week</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 d-flex flex-column justify-content-between">
                <div class="card-header">Leaderboard</div>
                <div class="card-body">
                    @if($leaderboard->count())
                    <ol class="list-group list-group-numbered">
                        @foreach($leaderboard as $entry)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $entry['name'] }}
                            <span class="badge bg-primary">{{ $entry['points'] }} pts</span>
                        </li>
                        @endforeach
                    </ol>
                    @else
                    <p>No leaderboard data yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('submit', '.ajax-complete-chore', function(e) {
    e.preventDefault();
    var $form = $(this);
    var $btn = $form.find('button');
    $btn.prop('disabled', true);
    $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: $form.serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function() {
            // Update badge and remove button
            $form.closest('li').find('.badge').removeClass('bg-secondary').addClass('bg-success')
                .text('Completed');
            $form.remove();
            // Optionally, update progress bar and points via AJAX or reload part of the page
            location.reload(); // simplest for now
        },
        error: function() {
            alert('Failed to mark as complete.');
            $btn.prop('disabled', false);
        }
    });
});
</script>
@endpush
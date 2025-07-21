@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Chores</h2>
        <a href="{{ route('chores.create') }}" class="btn btn-success">Create Chore</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('chores.index') }}" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search chores by name or description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    @if(request('search'))
                    <a href="{{ route('chores.index') }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="successModalMessage">{{ session('success') }}</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this chore?
                </div>
                <div class="modal-footer">
                    <form id="deleteChoreForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="col-name">Name</th>
                            <th style="min-width: 250px; max-width: 450px;">Description</th>
                            <th class="col-points">Points</th>
                            <th class="col-frequency">Frequency</th>
                            <th class="col-priority">Priority</th>
                            <th class="col-created-by">Created By</th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chores as $chore)
                        <tr>
                            <td class="col-name">
                                <div class="text-truncate" title="{{ $chore->name }}">
                                    {{ $chore->name }}
                                </div>
                            </td>
                            <td style="min-width: 250px; max-width: 450px;">
                                <div class="text-truncate" title="{{ $chore->description }}">
                                    {{ $chore->description ?: 'No description' }}
                                </div>
                            </td>
                            <td class="col-points text-center">
                                {{ $chore->points }}
                            </td>
                            <td class="col-frequency text-center">
                                <span
                                    class="badge bg-{{ $chore->frequency === 'daily' ? 'success' : ($chore->frequency === 'weekly' ? 'warning' : ($chore->frequency === 'monthly' ? 'info' : 'secondary')) }}">
                                    {{ ucfirst($chore->frequency) }}
                                    @if($chore->frequency !== 'one-time')
                                    <i class="fas fa-redo-alt ms-1" title="Recurring"></i>
                                    @endif
                                </span>
                            </td>
                            <td class="col-priority text-center">
                                <span
                                    class="badge bg-{{ $chore->priority === 'high' ? 'danger' : ($chore->priority === 'medium' ? 'warning' : 'info') }}">
                                    {{ ucfirst($chore->priority) }}
                                </span>
                            </td>
                            <td class="col-created-by">
                                <div class="text-truncate" title="{{ $chore->creator->name ?? 'N/A' }}">
                                    {{ $chore->creator->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="col-actions">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('chores.edit', $chore) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <button class="btn btn-sm btn-danger btn-delete-chore"
                                        data-chore-id="{{ $chore->id }}"
                                        data-action="{{ route('chores.destroy', $chore) }}"
                                        type="button">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No chores found. <a href="{{ route('chores.create') }}">Create your
                                            first chore</a></p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if($chores->total() > 0)
    <div class="mb-3 text-muted">
        Showing {{ $chores->firstItem() }} to {{ $chores->lastItem() }} of {{ $chores->total() }} chores
        (Page {{ $chores->currentPage() }} of {{ $chores->lastPage() }})
    </div>
    @endif
    @if($chores->hasPages())
    <div class="pagination-container">
        {{ $chores->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation modal logic
    let deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    let deleteChoreForm = document.getElementById('deleteChoreForm');
    document.querySelectorAll('.btn-delete-chore').forEach(function(btn) {
        btn.addEventListener('click', function() {
            let action = btn.getAttribute('data-action');
            deleteChoreForm.setAttribute('action', action);
            console.log('Delete button clicked, showing modal for action:', action);
            if (deleteModal) {
                deleteModal.show();
            } else {
                alert('Delete modal not found!');
            }
        });
    });
});
</script>
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
});
</script>
@endif
@endpush

@endsection
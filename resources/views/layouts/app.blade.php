<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChoreBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    /* Pagination Styling */
    .pagination {
        margin-bottom: 0;
        gap: 2px;
    }

    .page-link {
        color: #2d3a3a !important;
        border-color: #e0e0e0 !important;
        background-color: #ffffff !important;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }

    .page-link:hover {
        color: #2d3a3a !important;
        background-color: #f5f6f7 !important;
        border-color: #e0e0e0 !important;
        transform: translateY(-1px);
    }

    .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(45, 58, 58, 0.25) !important;
        outline: none;
    }

    .page-item.active .page-link {
        background-color: #2d3a3a !important;
        border-color: #2d3a3a !important;
        color: #ffffff !important;
    }

    .page-item.disabled .page-link {
        color: #7d8b8b !important;
        border-color: #e0e0e0 !important;
        background-color: #f8f9fa !important;
        cursor: not-allowed;
    }

    .page-item.disabled .page-link:hover {
        transform: none;
    }

    /* Ensure pagination container is properly styled */
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 1rem;
    }

    /* Fix for pagination on different pages */
    .pagination .page-item:not(:first-child) .page-link {
        margin-left: -1px;
    }

    /* Ensure proper spacing between pagination elements */
    .pagination .page-item {
        margin: 0 1px;
    }

    /* Additional fixes for pagination consistency */
    .pagination .page-item:first-child .page-link {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }

    .pagination .page-item:last-child .page-link {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }

    /* Ensure pagination works with search parameters */
    .pagination a {
        text-decoration: none;
    }

    /* Fix for pagination on mobile */
    @media (max-width: 768px) {
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination .page-item {
            margin: 2px;
        }
    }

    /* Table layout constraints */
    .table {
        table-layout: fixed;
        width: 100%;
    }

    .table th,
    .table td {
        overflow: hidden;
        white-space: nowrap;
    }

    /* Scalable column width classes */

    .col-name {
        min-width: 180px !important;
        max-width: 240px !important;
        width: 180px !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .col-points {
        width: 60px !important;
        min-width: 60px !important;
        max-width: 60px !important;
        flex-shrink: 0;
    }

    .col-frequency {
        width: 100px !important;
        min-width: 100px !important;
        max-width: 100px !important;
        flex-shrink: 0;
    }

    .col-priority {
        width: 90px !important;
        min-width: 90px !important;
        max-width: 90px !important;
        flex-shrink: 0;
    }

    .col-created-by {
        width: 130px !important;
        min-width: 130px !important;
        max-width: 130px !important;
        flex-shrink: 0;
    }

    .col-actions {
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
        flex-shrink: 0;
    }

    /* Limit name column max width for better description space */
    th[style*='min-width: 120px'][style*='max-width: 180px'],
    td[style*='min-width: 120px'][style*='max-width: 180px'] {
        max-width: 180px !important;
        min-width: 120px !important;
        width: 120px !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Badge styling for better fit */
    .badge {
        font-size: 0.75rem !important;
        white-space: nowrap !important;
    }

    /* Ensure badges don't overflow */
    .col-frequency .badge,
    .col-priority .badge {
        max-width: 100% !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Ensure buttons don't wrap in action column */
    .d-flex.gap-1 {
        flex-wrap: nowrap;
    }

    .d-flex.gap-1 .btn {
        flex-shrink: 0;
    }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">ChoreBoard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="btn btn-link nav-link" type="submit">Logout</button>
                        </form>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <div class="container mt-3">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
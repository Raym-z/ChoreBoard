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
                @php
                $user = Auth::user();
                $households = $user->households;
                $currentHouseholdId = session('current_household_id', $households->first()?->id);
                $currentHousehold = $households->firstWhere('id', $currentHouseholdId);
                @endphp
                @if($households->count() > 1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="householdDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $currentHousehold?->name ?? 'Household' }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="householdDropdown">
                        @foreach($households as $household)
                        <li>
                            <form method="POST" action="{{ route('household.switch', $household->id) }}"
                                class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item {{ $household->id == $currentHouseholdId ? 'active' : '' }}">
                                    {{ $household->name }}
                                    @if($household->id == $currentHouseholdId)
                                    <span class="badge bg-success ms-2">Current</span>
                                    @endif
                                </button>
                            </form>
                        </li>
                        @endforeach
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('household.show') }}">Manage Households</a></li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('household.show') }}">Household</a>
                </li>
                @endif
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
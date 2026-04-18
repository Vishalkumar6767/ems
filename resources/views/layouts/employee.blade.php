<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Employee Portal</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar" style="background: linear-gradient(135deg, #059669 0%, #064e3b 100%);">
            <div class="sidebar-brand">
                <h4>Employee Portal</h4>
                <small>{{ Auth::user()->factory->name ?? '' }}</small>
            </div>

            <div class="nav-section mt-3">Menu</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i> My Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employee.attendance') }}" class="nav-link {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i> My Attendance
                    </a>
                </li>
            </ul>

            <div class="mt-auto p-3" style="position: absolute; bottom: 0; width: 100%;">
                <div class="d-flex align-items-center text-white mb-2" style="font-size: 0.85rem;">
                    <i class="bi bi-person-circle me-2"></i>
                    {{ Auth::user()->name }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">
                        <i class="bi bi-box-arrow-left me-1"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <div class="main-content w-100">
            <div class="topbar">
                <div>
                    <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
                    <small class="text-muted">@yield('subtitle', '')</small>
                </div>
                <div class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i> {{ now()->format('l, M d, Y') }}
                </div>
            </div>

            @yield('content')
        </div>
    </div>
</body>
</html>

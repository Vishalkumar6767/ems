<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Attendance Maker</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar">
            <div class="sidebar-brand">
                <h4>Attendance Maker</h4>
                <small>Factory Management</small>
            </div>

            <div class="nav-section mt-3">Main</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                </li>
            </ul>

            <div class="nav-section mt-2">Management</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('factories.index') }}" class="nav-link {{ request()->routeIs('factories.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> Factories
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('floors.index') }}" class="nav-link {{ request()->routeIs('floors.*') ? 'active' : '' }}">
                        <i class="bi bi-layers"></i> Floors
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('workers.index') }}" class="nav-link {{ request()->routeIs('workers.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Workers
                    </a>
                </li>
            </ul>

            <div class="nav-section mt-2">Attendance</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('attendance.mark') }}" class="nav-link {{ request()->routeIs('attendance.mark') ? 'active' : '' }}">
                        <i class="bi bi-check2-square"></i> Mark Attendance
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('attendance.import') }}" class="nav-link {{ request()->routeIs('attendance.import') ? 'active' : '' }}">
                        <i class="bi bi-upload"></i> Import CSV
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('attendance.report') }}" class="nav-link {{ request()->routeIs('attendance.report') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i> Reports
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

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>

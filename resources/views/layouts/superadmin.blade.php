<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Super Admin</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- Mobile Top Bar -->
    <div class="mobile-topbar">
        <div class="mobile-brand">
            <div class="mobile-logo" style="background: linear-gradient(135deg, #7c3aed, #6d28d9);"><span class="logo-letter">V</span></div>
            <span class="mobile-title">VEMS <span class="badge bg-warning text-dark" style="font-size: 0.6rem;">SUPER</span></span>
        </div>
        <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle menu">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="d-flex">
        <nav class="sidebar" id="sidebar" style="background: linear-gradient(135deg, #7c3aed 0%, #4c1d95 100%);">
            <div class="sidebar-brand">
                <div class="sidebar-logo" style="background: linear-gradient(135deg, #a78bfa, #7c3aed);"><span class="logo-letter">V</span></div>
                <div class="brand-text">
                    <h4>VEMS</h4>
                    <small><span class="badge bg-warning text-dark">SUPER ADMIN</span></small>
                </div>
            </div>

            <div class="nav-section mt-3">Overview</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
            </ul>

            <div class="nav-section mt-2">Management</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('superadmin.companies.index') }}" class="nav-link {{ request()->routeIs('superadmin.companies.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> Companies
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('superadmin.users.index') }}" class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i> Users & Admins
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('superadmin.workers.index') }}" class="nav-link {{ request()->routeIs('superadmin.workers.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> All Workers
                    </a>
                </li>
            </ul>

            <div class="nav-section mt-2">Reports</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('superadmin.attendance.report') }}" class="nav-link {{ request()->routeIs('superadmin.attendance.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i> Attendance Report
                    </a>
                </li>
            </ul>

            <div class="mt-auto p-3" style="position: absolute; bottom: 0; width: 100%;">
                <div class="d-flex align-items-center text-white mb-2" style="font-size: 0.85rem;">
                    <i class="bi bi-shield-check me-2"></i>
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.querySelector('.sidebar-overlay').classList.toggle('show');
            document.body.style.overflow = document.getElementById('sidebar').classList.contains('show') ? 'hidden' : '';
        }
        document.querySelectorAll('.sidebar .nav-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) { toggleSidebar(); }
            });
        });
    </script>
</body>
</html>

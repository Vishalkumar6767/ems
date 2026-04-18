<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Attendance Maker</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="text-center mb-4">
                <span style="font-size: 3rem; color: #3b82f6;"><i class="bi bi-building-check"></i></span>
                <h3 class="fw-bold mt-2">Attendance Maker</h3>
                <p class="text-muted">Sign in to continue</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    @foreach($errors->all() as $error)<small>{{ $error }}</small>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                </button>
            </form>

            <div class="mt-4 p-3 bg-light rounded" style="font-size: 0.8rem;">
                <div class="fw-bold mb-2">Demo Credentials:</div>
                <table class="table table-sm table-borderless mb-0" style="font-size: 0.8rem;">
                    <tr>
                        <td><span class="badge bg-warning text-dark">Super Admin</span></td>
                        <td>superadmin@system.com</td>
                        <td class="text-muted">password</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-info text-dark">Company Admin</span></td>
                        <td>admin@tatasteel.com</td>
                        <td class="text-muted">password</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-success">Employee</span></td>
                        <td>rajesh.kumar.singh@factory.com</td>
                        <td class="text-muted">password</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

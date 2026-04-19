@extends('layouts.superadmin')
@section('title', $worker->name)
@section('subtitle', $worker->employee_id . ' - ' . $worker->floor->name . ', ' . $worker->floor->factory->name)

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-1"></i> Worker Details</h6>
            <div class="table-responsive"><table class="table table-sm table-borderless mb-0">
                <tr><td class="text-muted" width="120">Name</td><td class="fw-semibold">{{ $worker->name }}</td></tr>
                <tr><td class="text-muted">Employee ID</td><td><code>{{ $worker->employee_id }}</code></td></tr>
                <tr><td class="text-muted">Designation</td><td>{{ $worker->designation ?? '-' }}</td></tr>
                <tr><td class="text-muted">Phone</td><td>{{ $worker->phone ?? '-' }}</td></tr>
                <tr><td class="text-muted">Company</td><td>{{ $worker->floor->factory->name }}</td></tr>
                <tr><td class="text-muted">Floor</td><td>{{ $worker->floor->name }}</td></tr>
                <tr><td class="text-muted">Login</td><td>{!! $worker->user ? '<span class="badge bg-success">Active</span> ' . $worker->user->email : '<span class="badge bg-secondary">No account</span>' !!}</td></tr>
            </table></div>
        </div>
    </div>
    <div class="col-md-8">
        <h6 class="fw-bold text-uppercase text-muted mb-3">This Month</h6>
        <div class="row g-3 mb-4">
            <div class="col-3"><div class="stat-card text-center"><div class="stat-value text-success">{{ $monthStats['present'] }}</div><div class="stat-label">Present</div></div></div>
            <div class="col-3"><div class="stat-card text-center"><div class="stat-value text-danger">{{ $monthStats['absent'] }}</div><div class="stat-label">Absent</div></div></div>
            <div class="col-3"><div class="stat-card text-center"><div class="stat-value text-warning">{{ $monthStats['late'] }}</div><div class="stat-label">Late</div></div></div>
            <div class="col-3"><div class="stat-card text-center"><div class="stat-value text-primary">{{ $monthStats['half_day'] }}</div><div class="stat-label">Half Day</div></div></div>
        </div>
        <div class="table-card">
            <div class="table-responsive"><table class="table table-hover">
                <thead><tr><th>Date</th><th>Status</th><th>Check In</th><th>Check Out</th></tr></thead>
                <tbody>
                    @forelse($recentAttendance as $att)
                        <tr>
                            <td>{{ $att->date->format('d M Y, l') }}</td>
                            <td><span class="badge badge-{{ $att->status }}">{{ ucfirst(str_replace('_', ' ', $att->status)) }}</span></td>
                            <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('h:i A') : '-' }}</td>
                            <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('h:i A') : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No records.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </div>
    </div>
</div>
<div class="mt-3"><a href="{{ route('superadmin.workers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a></div>
@endsection

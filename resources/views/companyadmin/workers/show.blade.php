@extends('layouts.companyadmin')
@section('title', $worker->name)
@section('subtitle', $worker->employee_id)

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-1"></i> Details</h6>
            <div class="table-responsive"><table class="table table-sm table-borderless mb-0">
                <tr><td class="text-muted" width="110">Name</td><td class="fw-semibold">{{ $worker->name }}</td></tr>
                <tr><td class="text-muted">Employee ID</td><td><code>{{ $worker->employee_id }}</code></td></tr>
                <tr><td class="text-muted">Designation</td><td>{{ $worker->designation ?? '-' }}</td></tr>
                <tr><td class="text-muted">Phone</td><td>{{ $worker->phone ?? '-' }}</td></tr>
                <tr><td class="text-muted">Floor</td><td>{{ $worker->floor->name }}</td></tr>
                <tr><td class="text-muted">Login</td><td>{!! $worker->user ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">None</span>' !!}</td></tr>
            </table></div>
        </div>
    </div>
    <div class="col-md-8">
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
<div class="mt-3"><a href="{{ route('companyadmin.workers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a></div>
@endsection

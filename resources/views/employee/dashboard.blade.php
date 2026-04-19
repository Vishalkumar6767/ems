@extends('layouts.employee')
@section('title', 'My Dashboard')
@section('subtitle', 'Welcome, ' . $worker->name)

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-1"></i> My Profile</h6>
            <div class="table-responsive"><table class="table table-sm table-borderless mb-0">
                <tr><td class="text-muted" width="110">Name</td><td class="fw-semibold">{{ $worker->name }}</td></tr>
                <tr><td class="text-muted">Employee ID</td><td><code>{{ $worker->employee_id }}</code></td></tr>
                <tr><td class="text-muted">Designation</td><td>{{ $worker->designation ?? '-' }}</td></tr>
                <tr><td class="text-muted">Company</td><td>{{ $worker->floor->factory->name }}</td></tr>
                <tr><td class="text-muted">Floor</td><td>{{ $worker->floor->name }}</td></tr>
                <tr><td class="text-muted">Phone</td><td>{{ $worker->phone ?? '-' }}</td></tr>
            </table></div>
        </div>

        <div class="stat-card mt-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-calendar-check me-1"></i> Today</h6>
            @if($todayAttendance)
                <div class="text-center">
                    <span class="badge badge-{{ $todayAttendance->status }} fs-6 px-3 py-2">
                        {{ ucfirst(str_replace('_', ' ', $todayAttendance->status)) }}
                    </span>
                    <div class="mt-2 text-muted">
                        @if($todayAttendance->check_in)
                            In: {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('h:i A') }}
                        @endif
                        @if($todayAttendance->check_out)
                            &middot; Out: {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('h:i A') }}
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center text-muted">
                    <i class="bi bi-clock" style="font-size: 2rem;"></i>
                    <p class="mt-1 mb-0">Not marked yet</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-8">
        <h6 class="fw-bold text-uppercase text-muted mb-3"><i class="bi bi-graph-up me-1"></i> This Month's Summary</h6>
        <div class="row g-3 mb-4">
            <div class="col-3"><div class="stat-card text-center"><div class="stat-value text-success">{{ $monthStats['present'] }}</div><div class="stat-label">Present</div></div></div>
            <div class="col-3"><div class="stat-card text-center"><div class="stat-value text-danger">{{ $monthStats['absent'] }}</div><div class="stat-label">Absent</div></div></div>
            <div class="col-3"><div class="stat-card text-center"><div class="stat-value text-warning">{{ $monthStats['late'] }}</div><div class="stat-label">Late</div></div></div>
            <div class="col-3"><div class="stat-card text-center"><div class="stat-value text-primary">{{ $monthStats['half_day'] }}</div><div class="stat-label">Half Day</div></div></div>
        </div>

        @php
            $totalDays = $monthStats['present'] + $monthStats['absent'] + $monthStats['late'] + $monthStats['half_day'];
            $attendancePct = $totalDays > 0 ? round((($monthStats['present'] + $monthStats['late'] + $monthStats['half_day']) / $totalDays) * 100) : 0;
        @endphp
        <div class="stat-card mb-4">
            <div class="d-flex justify-content-between mb-2">
                <span class="fw-bold">Attendance Rate</span>
                <span class="fw-bold text-{{ $attendancePct >= 80 ? 'success' : ($attendancePct >= 60 ? 'warning' : 'danger') }}">{{ $attendancePct }}%</span>
            </div>
            <div class="progress" style="height: 12px;">
                <div class="progress-bar bg-{{ $attendancePct >= 80 ? 'success' : ($attendancePct >= 60 ? 'warning' : 'danger') }}" style="width: {{ $attendancePct }}%;"></div>
            </div>
        </div>

        <h6 class="fw-bold text-uppercase text-muted mb-3"><i class="bi bi-clock-history me-1"></i> Last 7 Days</h6>
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
                        <tr><td colspan="4" class="text-center text-muted py-3">No records yet.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', $worker->name)
@section('subtitle', $worker->employee_id . ' - ' . $worker->floor->name . ', ' . $worker->floor->factory->name)

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card">
            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-1"></i> Worker Details</h6>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" width="120">Name</td>
                    <td class="fw-semibold">{{ $worker->name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Employee ID</td>
                    <td><code>{{ $worker->employee_id }}</code></td>
                </tr>
                <tr>
                    <td class="text-muted">Phone</td>
                    <td>{{ $worker->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Factory</td>
                    <td>{{ $worker->floor->factory->name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Floor</td>
                    <td>{{ $worker->floor->name }} (#{{ $worker->floor->floor_number }})</td>
                </tr>
                <tr>
                    <td class="text-muted">Joined</td>
                    <td>{{ $worker->created_at->format('d M Y') }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-8">
        <h6 class="fw-bold text-uppercase text-muted mb-3"><i class="bi bi-calendar3 me-1"></i> This Month's Summary</h6>
        <div class="row g-3 mb-4">
            <div class="col-3">
                <div class="stat-card text-center">
                    <div class="stat-value text-success">{{ $monthStats['present'] }}</div>
                    <div class="stat-label">Present</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card text-center">
                    <div class="stat-value text-danger">{{ $monthStats['absent'] }}</div>
                    <div class="stat-label">Absent</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card text-center">
                    <div class="stat-value text-warning">{{ $monthStats['late'] }}</div>
                    <div class="stat-label">Late</div>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card text-center">
                    <div class="stat-value text-primary">{{ $monthStats['half_day'] }}</div>
                    <div class="stat-label">Half Day</div>
                </div>
            </div>
        </div>

        <h6 class="fw-bold text-uppercase text-muted mb-3"><i class="bi bi-clock-history me-1"></i> Recent Attendance</h6>
        <div class="table-card">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAttendance as $att)
                        <tr>
                            <td>{{ $att->date->format('d M Y, l') }}</td>
                            <td>
                                <span class="badge badge-{{ $att->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $att->status)) }}
                                </span>
                            </td>
                            <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('h:i A') : '-' }}</td>
                            <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('h:i A') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No attendance records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('workers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
    <a href="{{ route('workers.edit', $worker) }}" class="btn btn-outline-warning"><i class="bi bi-pencil me-1"></i> Edit</a>
</div>
@endsection

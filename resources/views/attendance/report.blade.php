@extends('layouts.app')

@section('title', 'Attendance Reports')
@section('subtitle', 'Filter and view attendance data')

@section('content')
<div class="table-card p-4 mb-4">
    <form method="GET" action="{{ route('attendance.report') }}" class="row g-3 align-items-end">
        <div class="col-md-2">
            <label class="form-label fw-semibold">Factory</label>
            <select name="factory_id" class="form-select">
                <option value="">All Factories</option>
                @foreach($factories as $factory)
                    <option value="{{ $factory->id }}" {{ request('factory_id') == $factory->id ? 'selected' : '' }}>
                        {{ $factory->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label fw-semibold">Floor</label>
            <select name="floor_id" class="form-select">
                <option value="">All Floors</option>
                @foreach($floors as $floor)
                    <option value="{{ $floor->id }}" {{ request('floor_id') == $floor->id ? 'selected' : '' }}>
                        {{ $floor->factory->name }} - {{ $floor->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">From Date</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">To Date</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to', now()->format('Y-m-d')) }}">
        </div>
        <div class="col-6 col-md-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-funnel me-1"></i> Generate Report
            </button>
        </div>
    </form>
</div>

@if($summary)
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div></div>
        <div class="d-flex gap-2">
            <a href="{{ route('attendance.export', ['date' => request('date_from')]) }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-arrow-down me-1"></i> Export Day CSV
            </a>
            <a href="{{ route('attendance.export.report', request()->query()) }}" class="btn btn-success">
                <i class="bi bi-download me-1"></i> Export Report CSV
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col">
            <div class="stat-card text-center">
                <div class="stat-value">{{ $summary['total'] }}</div>
                <div class="stat-label">Total Records</div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card text-center">
                <div class="stat-value text-success">{{ $summary['present'] }}</div>
                <div class="stat-label">Present</div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card text-center">
                <div class="stat-value text-danger">{{ $summary['absent'] }}</div>
                <div class="stat-label">Absent</div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card text-center">
                <div class="stat-value text-warning">{{ $summary['late'] }}</div>
                <div class="stat-label">Late</div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card text-center">
                <div class="stat-value text-primary">{{ $summary['half_day'] }}</div>
                <div class="stat-label">Half Day</div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-responsive"><table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Employee ID</th>
                    <th>Worker</th>
                    <th>Floor</th>
                    <th>Factory</th>
                    <th>Status</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $i => $att)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $att->date->format('d M Y') }}</td>
                        <td><code>{{ $att->worker->employee_id }}</code></td>
                        <td class="fw-semibold">
                            <a href="{{ route('workers.show', $att->worker) }}" class="text-decoration-none">{{ $att->worker->name }}</a>
                        </td>
                        <td>{{ $att->worker->floor->name }}</td>
                        <td>{{ $att->worker->floor->factory->name }}</td>
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
                        <td colspan="9" class="text-center text-muted py-4">No attendance records found for the selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table></div>
    </div>
@else
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bar-chart" style="font-size: 3rem;"></i>
        <p class="mt-2">Select filters and click "Generate Report" to view attendance data.</p>
    </div>
@endif
@endsection

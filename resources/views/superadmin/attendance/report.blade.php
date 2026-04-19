@extends('layouts.superadmin')
@section('title', 'Attendance Report')
@section('subtitle', 'Global attendance data')

@section('content')
<div class="table-card p-4 mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-2">
            <label class="form-label fw-semibold">Company</label>
            <select name="factory_id" class="form-select">
                <option value="">All</option>
                @foreach($factories as $f)
                    <option value="{{ $f->id }}" {{ request('factory_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label fw-semibold">Floor</label>
            <select name="floor_id" class="form-select">
                <option value="">All</option>
                @foreach($floors as $fl)
                    <option value="{{ $fl->id }}" {{ request('floor_id') == $fl->id ? 'selected' : '' }}>{{ $fl->factory->name }} - {{ $fl->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to', now()->format('Y-m-d')) }}">
        </div>
        <div class="col-6 col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i> Generate</button>
        </div>
    </form>
</div>

@if($summary)
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('superadmin.attendance.export', request()->query()) }}" class="btn btn-success"><i class="bi bi-download me-1"></i> Export CSV</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col"><div class="stat-card text-center"><div class="stat-value">{{ $summary['total'] }}</div><div class="stat-label">Total</div></div></div>
        <div class="col"><div class="stat-card text-center"><div class="stat-value text-success">{{ $summary['present'] }}</div><div class="stat-label">Present</div></div></div>
        <div class="col"><div class="stat-card text-center"><div class="stat-value text-danger">{{ $summary['absent'] }}</div><div class="stat-label">Absent</div></div></div>
        <div class="col"><div class="stat-card text-center"><div class="stat-value text-warning">{{ $summary['late'] }}</div><div class="stat-label">Late</div></div></div>
        <div class="col"><div class="stat-card text-center"><div class="stat-value text-primary">{{ $summary['half_day'] }}</div><div class="stat-label">Half Day</div></div></div>
    </div>

    <div class="table-card">
        <div class="table-responsive"><table class="table table-hover">
            <thead><tr><th>#</th><th>Date</th><th>Employee ID</th><th>Worker</th><th>Floor</th><th>Company</th><th>Status</th><th>In</th><th>Out</th></tr></thead>
            <tbody>
                @forelse($attendances as $i => $att)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $att->date->format('d M Y') }}</td>
                        <td><code>{{ $att->worker->employee_id }}</code></td>
                        <td><a href="{{ route('superadmin.workers.show', $att->worker) }}" class="text-decoration-none fw-semibold">{{ $att->worker->name }}</a></td>
                        <td>{{ $att->worker->floor->name }}</td>
                        <td>{{ $att->worker->floor->factory->name }}</td>
                        <td><span class="badge badge-{{ $att->status }}">{{ ucfirst(str_replace('_', ' ', $att->status)) }}</span></td>
                        <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('h:i A') : '-' }}</td>
                        <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('h:i A') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No records found.</td></tr>
                @endforelse
            </tbody>
        </table></div>
    </div>
@else
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bar-chart" style="font-size: 3rem;"></i>
        <p class="mt-2">Select filters and click "Generate" to view attendance data.</p>
    </div>
@endif
@endsection

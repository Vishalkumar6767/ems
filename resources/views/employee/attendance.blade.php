@extends('layouts.employee')
@section('title', 'My Attendance')
@section('subtitle', $worker->name . ' - ' . $worker->employee_id)

@section('content')
<div class="table-card p-4 mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Month</label>
            <select name="month" class="form-select">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Year</label>
            <select name="year" class="form-select">
                @for($y = now()->year; $y >= now()->year - 2; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> View</button>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col"><div class="stat-card text-center"><div class="stat-value">{{ $summary['total'] }}</div><div class="stat-label">Total Days</div></div></div>
    <div class="col"><div class="stat-card text-center"><div class="stat-value text-success">{{ $summary['present'] }}</div><div class="stat-label">Present</div></div></div>
    <div class="col"><div class="stat-card text-center"><div class="stat-value text-danger">{{ $summary['absent'] }}</div><div class="stat-label">Absent</div></div></div>
    <div class="col"><div class="stat-card text-center"><div class="stat-value text-warning">{{ $summary['late'] }}</div><div class="stat-label">Late</div></div></div>
    <div class="col"><div class="stat-card text-center"><div class="stat-value text-primary">{{ $summary['half_day'] }}</div><div class="stat-label">Half Day</div></div></div>
</div>

<div class="table-card">
    <div class="table-responsive"><table class="table table-hover">
        <thead><tr><th>#</th><th>Date</th><th>Day</th><th>Status</th><th>Check In</th><th>Check Out</th></tr></thead>
        <tbody>
            @forelse($attendances as $i => $att)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $att->date->format('d M Y') }}</td>
                    <td class="text-muted">{{ $att->date->format('l') }}</td>
                    <td><span class="badge badge-{{ $att->status }}">{{ ucfirst(str_replace('_', ' ', $att->status)) }}</span></td>
                    <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('h:i A') : '-' }}</td>
                    <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('h:i A') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No attendance records for this month.</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection

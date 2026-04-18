@extends('layouts.companyadmin')
@section('title', 'Dashboard')
@section('subtitle', $factory->name . ' - Today\'s overview')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="stat-card"><div class="stat-label mb-1">Workers</div><div class="stat-value">{{ $stats['total_workers'] }}</div></div>
    </div>
    <div class="col-md-2">
        <div class="stat-card"><div class="stat-label mb-1">Floors</div><div class="stat-value text-primary">{{ $stats['total_floors'] }}</div></div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center"><div class="stat-value text-success">{{ $stats['present'] }}</div><div class="stat-label">Present</div></div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center"><div class="stat-value text-danger">{{ $stats['absent'] }}</div><div class="stat-label">Absent</div></div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center"><div class="stat-value text-warning">{{ $stats['late'] }}</div><div class="stat-label">Late</div></div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center"><div class="stat-value text-primary">{{ $stats['half_day'] }}</div><div class="stat-label">Half Day</div></div>
    </div>
</div>

<h6 class="fw-bold text-uppercase text-muted mb-3"><i class="bi bi-layers me-1"></i> Floor-wise Attendance</h6>
<div class="row g-3">
    @foreach($floorStats as $fs)
        @php $pct = $fs['total_workers'] > 0 ? round(($fs['present'] / $fs['total_workers']) * 100) : 0; @endphp
        <div class="col-md-4">
            <div class="floor-card">
                <div class="floor-header">
                    <h6>{{ $fs['floor']->name }}</h6>
                    <small>Floor #{{ $fs['floor']->floor_number }} &middot; {{ $fs['total_workers'] }} workers</small>
                </div>
                <div class="floor-body">
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <span class="badge badge-present">{{ $fs['present'] }} Present</span>
                        <span class="badge badge-absent">{{ $fs['absent'] }} Absent</span>
                        <span class="badge badge-late">{{ $fs['late'] }} Late</span>
                        <span class="badge badge-half_day">{{ $fs['half_day'] }} Half Day</span>
                        @if($fs['not_marked'] > 0)
                            <span class="badge bg-secondary">{{ $fs['not_marked'] }} Not Marked</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Attendance</small><small class="fw-bold">{{ $pct }}%</small>
                    </div>
                    <div class="attendance-bar"><div class="progress-bar bg-success" style="width: {{ $pct }}%; height: 100%;"></div></div>
                    <div class="text-end mt-2">
                        <a href="{{ route('companyadmin.attendance.mark', ['floor_id' => $fs['floor']->id]) }}" class="btn btn-sm btn-outline-primary">Mark <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

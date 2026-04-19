@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Today\'s attendance overview')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Total Workers</div>
                    <div class="stat-value">{{ $totalWorkers }}</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Present Today</div>
                    <div class="stat-value text-success">{{ $totalPresent }}</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Absent Today</div>
                    <div class="stat-value text-danger">{{ $totalAbsent }}</div>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Late Today</div>
                    <div class="stat-value text-warning">{{ $totalLate }}</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-clock-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<h6 class="fw-bold text-uppercase text-muted mb-3">
    <i class="bi bi-layers me-1"></i> Floor-wise Attendance
</h6>

<div class="row g-3">
    @forelse($floorStats as $stat)
        @php
            $total = $stat['total_workers'];
            $marked = $total - $stat['not_marked'];
            $percentage = $total > 0 ? round(($stat['present'] / $total) * 100) : 0;
        @endphp
        <div class="col-6 col-md-4 col-lg-3">
            <div class="floor-card">
                <div class="floor-header">
                    <h6>{{ $stat['floor']->name }}</h6>
                    <small>{{ $stat['floor']->factory->name }} &middot; Floor #{{ $stat['floor']->floor_number }}</small>
                </div>
                <div class="floor-body">
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <span class="badge badge-present">{{ $stat['present'] }} Present</span>
                        <span class="badge badge-absent">{{ $stat['absent'] }} Absent</span>
                        <span class="badge badge-late">{{ $stat['late'] }} Late</span>
                        <span class="badge badge-half_day">{{ $stat['half_day'] }} Half Day</span>
                        @if($stat['not_marked'] > 0)
                            <span class="badge bg-secondary">{{ $stat['not_marked'] }} Not Marked</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Attendance Rate</small>
                        <small class="fw-bold">{{ $percentage }}%</small>
                    </div>
                    <div class="attendance-bar">
                        <div class="progress-bar bg-success" style="width: {{ $percentage }}%; height: 100%;"></div>
                    </div>
                    <div class="text-end mt-2">
                        <a href="{{ route('attendance.mark', ['floor_id' => $stat['floor']->id]) }}" class="btn btn-sm btn-outline-primary">
                            Mark <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-2">No floors configured yet. <a href="{{ route('floors.create') }}">Add a floor</a> to get started.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection

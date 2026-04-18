@extends('layouts.superadmin')

@section('title', 'Super Admin Dashboard')
@section('subtitle', 'Global overview of all companies')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Total Companies</div>
                    <div class="stat-value text-primary">{{ $stats['total_factories'] }}</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-building"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Total Workers</div>
                    <div class="stat-value">{{ $stats['total_workers'] }}</div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Present Today</div>
                    <div class="stat-value text-success">{{ $stats['present_today'] }}</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Absent Today</div>
                    <div class="stat-value text-danger">{{ $stats['absent_today'] }}</div>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle-fill"></i></div>
            </div>
        </div>
    </div>
</div>

<h6 class="fw-bold text-uppercase text-muted mb-3"><i class="bi bi-building me-1"></i> Company-wise Attendance Today</h6>

<div class="row g-3">
    @foreach($factoryStats as $fs)
        @php
            $pct = $fs['total_workers'] > 0 ? round(($fs['present'] / $fs['total_workers']) * 100) : 0;
        @endphp
        <div class="col-md-4">
            <div class="floor-card">
                <div class="floor-header" style="background: linear-gradient(135deg, #7c3aed, #4c1d95);">
                    <h6>{{ $fs['factory']->name }}</h6>
                    <small>{{ $fs['total_workers'] }} workers &middot; {{ $fs['factory']->floors_count }} floors</small>
                </div>
                <div class="floor-body">
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <span class="badge badge-present">{{ $fs['present'] }} Present</span>
                        <span class="badge badge-absent">{{ $fs['absent'] }} Absent</span>
                        <span class="badge badge-late">{{ $fs['late'] }} Late</span>
                        <span class="badge badge-half_day">{{ $fs['half_day'] }} Half Day</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Attendance Rate</small>
                        <small class="fw-bold">{{ $pct }}%</small>
                    </div>
                    <div class="attendance-bar">
                        <div class="progress-bar bg-success" style="width: {{ $pct }}%; height: 100%;"></div>
                    </div>
                    <div class="text-end mt-2">
                        <a href="{{ route('superadmin.companies.show', $fs['factory']) }}" class="btn btn-sm btn-outline-primary">
                            View <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

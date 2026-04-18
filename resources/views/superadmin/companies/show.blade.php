@extends('layouts.superadmin')
@section('title', $company->name)
@section('subtitle', 'Company Details')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label mb-1">Floors</div>
            <div class="stat-value text-primary">{{ $company->floors->count() }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label mb-1">Total Workers</div>
            <div class="stat-value">{{ $totalWorkers }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <div class="stat-value text-success">{{ $stats['present'] }}</div>
            <div class="stat-label">Present Today</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <div class="stat-value text-danger">{{ $stats['absent'] }}</div>
            <div class="stat-label">Absent Today</div>
        </div>
    </div>
</div>

@if($admins->count())
<h6 class="fw-bold text-uppercase text-muted mb-2"><i class="bi bi-person-gear me-1"></i> Company Admins</h6>
<div class="table-card mb-4">
    <table class="table table-hover mb-0">
        <thead><tr><th>Name</th><th>Email</th></tr></thead>
        <tbody>
            @foreach($admins as $admin)
                <tr><td class="fw-semibold">{{ $admin->name }}</td><td>{{ $admin->email }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<h6 class="fw-bold text-uppercase text-muted mb-2"><i class="bi bi-layers me-1"></i> Floors</h6>
<div class="table-card">
    <table class="table table-hover mb-0">
        <thead><tr><th>#</th><th>Floor</th><th>Floor No.</th><th>Workers</th></tr></thead>
        <tbody>
            @foreach($company->floors as $floor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $floor->name }}</td>
                    <td><span class="badge bg-info text-dark">#{{ $floor->floor_number }}</span></td>
                    <td><span class="badge bg-primary">{{ $floor->workers_count }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3">
    <a href="{{ route('superadmin.companies.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
@endsection

@extends('layouts.app')

@section('title', $factory->name)
@section('subtitle', 'Factory Details')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Total Floors</div>
                    <div class="stat-value text-primary">{{ $factory->floors->count() }}</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-layers"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label mb-1">Total Workers</div>
                    <div class="stat-value text-success">{{ $totalWorkers }}</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label mb-1">Address</div>
            <div class="mt-1">{{ $factory->address ?? 'Not specified' }}</div>
        </div>
    </div>
</div>

<h6 class="fw-bold text-uppercase text-muted mt-4 mb-3"><i class="bi bi-layers me-1"></i> Floors in this Factory</h6>

<div class="table-card">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Floor Name</th>
                <th>Floor Number</th>
                <th>Workers</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factory->floors as $floor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $floor->name }}</td>
                    <td><span class="badge bg-info text-dark">#{{ $floor->floor_number }}</span></td>
                    <td><span class="badge bg-primary">{{ $floor->workers_count }}</span></td>
                    <td>
                        <a href="{{ route('floors.show', $floor) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('attendance.mark', ['floor_id' => $floor->id]) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-check2-square"></i> Attendance</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3">
    <a href="{{ route('factories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
    <a href="{{ route('factories.edit', $factory) }}" class="btn btn-outline-warning"><i class="bi bi-pencil me-1"></i> Edit</a>
</div>
@endsection

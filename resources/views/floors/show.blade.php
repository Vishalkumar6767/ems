@extends('layouts.app')

@section('title', $floor->name)
@section('subtitle', $floor->factory->name . ' - Floor #' . $floor->floor_number)

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label mb-1">Total Workers</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="stat-value text-success">{{ $stats['present'] }}</div>
            <div class="stat-label">Present</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="stat-value text-danger">{{ $stats['absent'] }}</div>
            <div class="stat-label">Absent</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="stat-value text-warning">{{ $stats['late'] }}</div>
            <div class="stat-label">Late</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-value text-primary">{{ $stats['half_day'] }}</div>
            <div class="stat-label">Half Day</div>
        </div>
    </div>
</div>

<h6 class="fw-bold text-uppercase text-muted mb-3"><i class="bi bi-people me-1"></i> Workers on this Floor</h6>

<div class="table-card">
    <div class="table-responsive"><table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($floor->workers as $worker)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><code>{{ $worker->employee_id }}</code></td>
                    <td class="fw-semibold">{{ $worker->name }}</td>
                    <td class="text-muted">{{ $worker->phone ?? '-' }}</td>
                    <td>
                        <a href="{{ route('workers.show', $worker) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table></div>
</div>

<div class="mt-3">
    <a href="{{ route('floors.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
    <a href="{{ route('attendance.mark', ['floor_id' => $floor->id]) }}" class="btn btn-success"><i class="bi bi-check2-square me-1"></i> Mark Attendance</a>
</div>
@endsection

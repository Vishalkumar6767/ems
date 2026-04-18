@extends('layouts.companyadmin')
@section('title', $floor->name)
@section('subtitle', 'Floor #' . $floor->floor_number)

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="stat-label mb-1">Workers</div><div class="stat-value">{{ $stats['total'] }}</div></div></div>
    <div class="col-md-2"><div class="stat-card text-center"><div class="stat-value text-success">{{ $stats['present'] }}</div><div class="stat-label">Present</div></div></div>
    <div class="col-md-2"><div class="stat-card text-center"><div class="stat-value text-danger">{{ $stats['absent'] }}</div><div class="stat-label">Absent</div></div></div>
    <div class="col-md-2"><div class="stat-card text-center"><div class="stat-value text-warning">{{ $stats['late'] }}</div><div class="stat-label">Late</div></div></div>
    <div class="col-md-3"><div class="stat-card text-center"><div class="stat-value text-primary">{{ $stats['half_day'] }}</div><div class="stat-label">Half Day</div></div></div>
</div>
<div class="table-card">
    <table class="table table-hover">
        <thead><tr><th>#</th><th>Employee ID</th><th>Name</th><th>Designation</th><th>Phone</th><th></th></tr></thead>
        <tbody>
            @foreach($floor->workers as $w)
                <tr>
                    <td>{{ $loop->iteration }}</td><td><code>{{ $w->employee_id }}</code></td><td class="fw-semibold">{{ $w->name }}</td>
                    <td class="text-muted">{{ $w->designation ?? '-' }}</td><td class="text-muted">{{ $w->phone ?? '-' }}</td>
                    <td><a href="{{ route('companyadmin.workers.show', $w) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">
    <a href="{{ route('companyadmin.floors.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
    <a href="{{ route('companyadmin.attendance.mark', ['floor_id' => $floor->id]) }}" class="btn btn-success"><i class="bi bi-check2-square me-1"></i> Mark Attendance</a>
</div>
@endsection

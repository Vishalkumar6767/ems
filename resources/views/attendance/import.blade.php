@extends('layouts.app')

@section('title', 'Import Attendance')
@section('subtitle', 'Upload a CSV file to import attendance data')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('attendance.import.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">CSV File</label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                    <div class="form-text">File must have columns: <code>employee_id, status, check_in, check_out</code></div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i> Import
                    </button>
                    <a href="{{ url('attendance_sample.csv') }}" class="btn btn-outline-secondary" download>
                        <i class="bi bi-download me-1"></i> Download Sample CSV
                    </a>
                </div>
            </form>
        </div>

        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="table-card p-4 mt-3">
                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle me-1"></i> Import Warnings</h6>
                <ul class="list-unstyled mb-0">
                    @foreach(session('import_errors') as $error)
                        <li class="text-muted py-1 border-bottom"><small>{{ $error }}</small></li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-card p-4 mt-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-1"></i> CSV Format Guide</h6>
            <p class="text-muted mb-2">Your CSV file should look like this:</p>
            <div class="bg-light p-3 rounded" style="font-family: monospace; font-size: 0.85rem;">
                employee_id,status,check_in,check_out<br>
                TATA-001,present,09:00,18:00<br>
                TATA-002,absent,,<br>
                TATA-003,late,10:30,18:00<br>
                BAJ-001,half_day,09:00,13:00
            </div>
            <div class="mt-3">
                <small class="text-muted">
                    <strong>Status values:</strong> present, absent, late, half_day<br>
                    <strong>Time format:</strong> HH:MM (24-hour) — leave blank for absent workers
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

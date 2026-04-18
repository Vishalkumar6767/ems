@extends('layouts.companyadmin')
@section('title', 'Import Attendance')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('companyadmin.attendance.import.store') }}" enctype="multipart/form-data">@csrf
                <div class="mb-3"><label class="form-label fw-semibold">Date</label><input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required></div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">CSV File</label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                    <div class="form-text">Columns: <code>employee_id, status, check_in, check_out</code></div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i> Import</button>
            </form>
        </div>
        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="table-card p-4 mt-3">
                <h6 class="fw-bold text-danger mb-2">Warnings</h6>
                @foreach(session('import_errors') as $e)<div class="text-muted border-bottom py-1"><small>{{ $e }}</small></div>@endforeach
            </div>
        @endif
    </div>
</div>
@endsection

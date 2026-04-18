@extends('layouts.companyadmin')
@section('title', 'Add Floor')
@section('content')
<div class="row justify-content-center"><div class="col-md-6"><div class="table-card p-4">
    <form method="POST" action="{{ route('companyadmin.floors.store') }}">@csrf
        <div class="mb-3"><label class="form-label fw-semibold">Floor Name</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
        <div class="mb-3"><label class="form-label fw-semibold">Floor Number</label><input type="number" name="floor_number" class="form-control" value="{{ old('floor_number', 0) }}" min="0" required></div>
        <div class="d-flex gap-2"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save</button><a href="{{ route('companyadmin.floors.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
    </form>
</div></div></div>
@endsection

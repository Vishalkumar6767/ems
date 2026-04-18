@extends('layouts.superadmin')
@section('title', 'Edit Company')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('superadmin.companies.update', $company) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Company Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $company->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $company->address) }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
                    <a href="{{ route('superadmin.companies.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

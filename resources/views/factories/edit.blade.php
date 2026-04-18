@extends('layouts.app')

@section('title', 'Edit Factory')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('factories.update', $factory) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Factory Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $factory->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $factory->address) }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
                    <a href="{{ route('factories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

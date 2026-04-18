@extends('layouts.app')

@section('title', 'Add Floor')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('floors.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Factory</label>
                    <select name="factory_id" class="form-select" required>
                        <option value="">Select Factory</option>
                        @foreach($factories as $factory)
                            <option value="{{ $factory->id }}" {{ old('factory_id') == $factory->id ? 'selected' : '' }}>
                                {{ $factory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Floor Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Ground Floor, Assembly Line A" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Floor Number</label>
                    <input type="number" name="floor_number" class="form-control" value="{{ old('floor_number', 0) }}" min="0" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save</button>
                    <a href="{{ route('floors.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

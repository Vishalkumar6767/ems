@extends('layouts.app')

@section('title', 'Edit Worker')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('workers.update', $worker) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Floor</label>
                    <select name="floor_id" class="form-select" required>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}" {{ old('floor_id', $worker->floor_id) == $floor->id ? 'selected' : '' }}>
                                {{ $floor->factory->name }} - {{ $floor->name }} (#{{ $floor->floor_number }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Worker Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $worker->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Employee ID</label>
                    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $worker->employee_id) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $worker->phone) }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
                    <a href="{{ route('workers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

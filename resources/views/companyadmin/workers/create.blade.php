@extends('layouts.companyadmin')
@section('title', 'Add Worker')
@section('content')
<div class="row justify-content-center"><div class="col-md-7"><div class="table-card p-4">
    <form method="POST" action="{{ route('companyadmin.workers.store') }}">@csrf
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label fw-semibold">Worker Name</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="col-md-6 mb-3"><label class="form-label fw-semibold">Employee ID</label><input type="text" name="employee_id" class="form-control" value="{{ old('employee_id') }}" required></div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Floor</label>
                <select name="floor_id" class="form-select" required>
                    <option value="">Select Floor</option>
                    @foreach($floors as $f)<option value="{{ $f->id }}" {{ old('floor_id') == $f->id ? 'selected' : '' }}>{{ $f->name }} (#{{ $f->floor_number }})</option>@endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3"><label class="form-label fw-semibold">Designation</label><input type="text" name="designation" class="form-control" value="{{ old('designation') }}"></div>
        </div>
        <div class="mb-3"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>

        <hr>
        <div class="form-check mb-3">
            <input type="checkbox" name="create_login" value="1" class="form-check-input" id="createLogin" {{ old('create_login') ? 'checked' : '' }} onchange="document.getElementById('loginFields').classList.toggle('d-none')">
            <label class="form-check-label fw-semibold" for="createLogin">Create employee login account</label>
        </div>
        <div id="loginFields" class="{{ old('create_login') ? '' : 'd-none' }}">
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label fw-semibold">Password</label><input type="password" name="password" class="form-control"></div>
            </div>
        </div>

        <div class="d-flex gap-2"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save</button><a href="{{ route('companyadmin.workers.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
    </form>
</div></div></div>
@endsection

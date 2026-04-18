@extends('layouts.superadmin')
@section('title', 'Edit User')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('superadmin.users.update', $user) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="company_admin" {{ old('role', $user->role) == 'company_admin' ? 'selected' : '' }}>Company Admin</option>
                        <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Company</label>
                    <select name="factory_id" class="form-select" required>
                        @foreach($factories as $f)
                            <option value="{{ $f->id }}" {{ old('factory_id', $user->factory_id) == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

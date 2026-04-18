@extends('layouts.superadmin')
@section('title', 'Users & Admins')
@section('subtitle', 'Manage company admins and employee accounts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" class="d-flex gap-2">
        <select name="role" class="form-select form-select-sm" style="width: 160px;" onchange="this.form.submit()">
            <option value="">All Roles</option>
            <option value="company_admin" {{ request('role') == 'company_admin' ? 'selected' : '' }}>Company Admin</option>
            <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
        </select>
        <select name="factory_id" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()">
            <option value="">All Companies</option>
            @foreach($factories as $f)
                <option value="{{ $f->id }}" {{ request('factory_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add User</a>
</div>

<div class="table-card">
    <table class="table table-hover">
        <thead>
            <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Company</th><th width="120">Actions</th></tr>
        </thead>
        <tbody>
            @forelse($users as $i => $user)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'company_admin')
                            <span class="badge bg-info text-dark">Company Admin</span>
                        @else
                            <span class="badge bg-secondary">Employee</span>
                        @endif
                    </td>
                    <td>{{ $user->factory->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

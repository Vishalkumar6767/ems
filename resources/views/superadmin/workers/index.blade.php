@extends('layouts.superadmin')
@section('title', 'All Workers')
@section('subtitle', 'View workers across all companies')

@section('content')
<div class="mb-3">
    <form method="GET" class="d-flex gap-2">
        <select name="factory_id" class="form-select form-select-sm" style="width: 220px;" onchange="this.form.submit()">
            <option value="">All Companies</option>
            @foreach($factories as $f)
                <option value="{{ $f->id }}" {{ request('factory_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive"><table class="table table-hover">
        <thead>
            <tr><th>#</th><th>Employee ID</th><th>Name</th><th>Designation</th><th>Phone</th><th>Floor</th><th>Company</th><th width="60"></th></tr>
        </thead>
        <tbody>
            @forelse($workers as $i => $w)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><code>{{ $w->employee_id }}</code></td>
                    <td class="fw-semibold">{{ $w->name }}</td>
                    <td class="text-muted">{{ $w->designation ?? '-' }}</td>
                    <td class="text-muted">{{ $w->phone ?? '-' }}</td>
                    <td>{{ $w->floor->name }}</td>
                    <td>{{ $w->floor->factory->name }}</td>
                    <td><a href="{{ route('superadmin.workers.show', $w) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No workers found.</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection

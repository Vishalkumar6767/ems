@extends('layouts.companyadmin')
@section('title', 'Workers')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('companyadmin.workers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Worker</a>
</div>
<div class="table-card">
    <table class="table table-hover">
        <thead><tr><th>#</th><th>Employee ID</th><th>Name</th><th>Designation</th><th>Floor</th><th>Phone</th><th>Login</th><th width="150">Actions</th></tr></thead>
        <tbody>
            @forelse($workers as $i => $w)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><code>{{ $w->employee_id }}</code></td>
                    <td class="fw-semibold">{{ $w->name }}</td>
                    <td class="text-muted">{{ $w->designation ?? '-' }}</td>
                    <td>{{ $w->floor->name }}</td>
                    <td class="text-muted">{{ $w->phone ?? '-' }}</td>
                    <td>{!! $w->user_id ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                    <td>
                        <a href="{{ route('companyadmin.workers.show', $w) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('companyadmin.workers.edit', $w) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('companyadmin.workers.destroy', $w) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No workers yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

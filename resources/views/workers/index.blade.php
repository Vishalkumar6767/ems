@extends('layouts.app')

@section('title', 'Workers')
@section('subtitle', 'Manage factory workers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('workers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Worker
    </a>
</div>

<div class="table-card">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Floor</th>
                <th>Factory</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($workers as $i => $worker)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><code>{{ $worker->employee_id }}</code></td>
                    <td class="fw-semibold">{{ $worker->name }}</td>
                    <td class="text-muted">{{ $worker->phone ?? '-' }}</td>
                    <td>{{ $worker->floor->name }}</td>
                    <td>{{ $worker->floor->factory->name }}</td>
                    <td>
                        <a href="{{ route('workers.show', $worker) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('workers.edit', $worker) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('workers.destroy', $worker) }}" class="d-inline" onsubmit="return confirm('Delete this worker?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No workers yet. Add one to get started.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

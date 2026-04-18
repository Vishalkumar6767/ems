@extends('layouts.app')

@section('title', 'Floors')
@section('subtitle', 'Manage factory floors')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('floors.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Floor
    </a>
</div>

<div class="table-card">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Floor Name</th>
                <th>Floor Number</th>
                <th>Factory</th>
                <th>Workers</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($floors as $i => $floor)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-semibold">{{ $floor->name }}</td>
                    <td><span class="badge bg-info text-dark">#{{ $floor->floor_number }}</span></td>
                    <td>{{ $floor->factory->name }}</td>
                    <td><span class="badge bg-primary">{{ $floor->workers_count }}</span></td>
                    <td>
                        <a href="{{ route('floors.show', $floor) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('floors.edit', $floor) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('floors.destroy', $floor) }}" class="d-inline" onsubmit="return confirm('Delete this floor and all its workers?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No floors yet. Add one to get started.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

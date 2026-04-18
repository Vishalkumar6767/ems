@extends('layouts.app')

@section('title', 'Factories')
@section('subtitle', 'Manage your factories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('factories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Factory
    </a>
</div>

<div class="table-card">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Address</th>
                <th>Floors</th>
                <th>Created</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($factories as $i => $factory)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-semibold">{{ $factory->name }}</td>
                    <td class="text-muted">{{ $factory->address ?? '-' }}</td>
                    <td><span class="badge bg-primary">{{ $factory->floors_count }}</span></td>
                    <td class="text-muted">{{ $factory->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('factories.show', $factory) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('factories.edit', $factory) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('factories.destroy', $factory) }}" class="d-inline" onsubmit="return confirm('Delete this factory and all its data?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No factories yet. Add one to get started.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

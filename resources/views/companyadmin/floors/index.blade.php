@extends('layouts.companyadmin')
@section('title', 'Floors')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('companyadmin.floors.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Floor</a>
</div>
<div class="table-card">
    <table class="table table-hover">
        <thead><tr><th>#</th><th>Name</th><th>Floor No.</th><th>Workers</th><th width="150">Actions</th></tr></thead>
        <tbody>
            @forelse($floors as $i => $f)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-semibold">{{ $f->name }}</td>
                    <td><span class="badge bg-info text-dark">#{{ $f->floor_number }}</span></td>
                    <td><span class="badge bg-primary">{{ $f->workers_count }}</span></td>
                    <td>
                        <a href="{{ route('companyadmin.floors.show', $f) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('companyadmin.floors.edit', $f) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('companyadmin.floors.destroy', $f) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No floors yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

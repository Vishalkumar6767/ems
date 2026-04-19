@extends('layouts.superadmin')

@section('title', 'Companies')
@section('subtitle', 'Manage all registered companies')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('superadmin.companies.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Company</a>
</div>

<div class="table-card">
    <div class="table-responsive"><table class="table table-hover">
        <thead>
            <tr><th>#</th><th>Company Name</th><th>Address</th><th>Floors</th><th>Created</th><th width="150">Actions</th></tr>
        </thead>
        <tbody>
            @forelse($factories as $i => $f)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-semibold">{{ $f->name }}</td>
                    <td class="text-muted">{{ Str::limit($f->address, 40) ?? '-' }}</td>
                    <td><span class="badge bg-primary">{{ $f->floors_count }}</span></td>
                    <td class="text-muted">{{ $f->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('superadmin.companies.show', $f) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('superadmin.companies.edit', $f) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('superadmin.companies.destroy', $f) }}" class="d-inline" onsubmit="return confirm('Delete this company?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No companies yet.</td></tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection

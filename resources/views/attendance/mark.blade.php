@extends('layouts.app')

@section('title', 'Mark Attendance')
@section('subtitle', 'Select a floor and mark worker attendance')

@section('content')
<div class="table-card p-4 mb-4">
    <form method="GET" action="{{ route('attendance.mark') }}" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label fw-semibold">Select Floor</label>
            <select name="floor_id" class="form-select" required>
                <option value="">-- Choose Floor --</option>
                @foreach($floors as $floor)
                    <option value="{{ $floor->id }}" {{ ($selectedFloor && $selectedFloor->id == $floor->id) ? 'selected' : '' }}>
                        {{ $floor->factory->name }} - {{ $floor->name }} (#{{ $floor->floor_number }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}">
        </div>
        <div class="col-6 col-md-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search me-1"></i> Load Workers
            </button>
        </div>
    </form>
</div>

@if($selectedFloor && $workers->count() > 0)
    <div class="table-card">
        <div class="p-3 border-bottom bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $selectedFloor->name }} - {{ $selectedFloor->factory->name }}</h6>
                    <small class="text-muted">{{ $workers->count() }} workers &middot; {{ \Carbon\Carbon::parse($date)->format('d M Y, l') }}</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="markAll('present')">All Present</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="markAll('absent')">All Absent</button>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('attendance.store') }}" class="attendance-form">
            @csrf
            <input type="hidden" name="floor_id" value="{{ $selectedFloor->id }}">
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="table-responsive"><table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th width="140">Check In</th>
                        <th width="140">Check Out</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workers as $i => $worker)
                        @php
                            $existing = $worker->attendances->first();
                            $currentStatus = $existing ? $existing->status : 'present';
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><code>{{ $worker->employee_id }}</code></td>
                            <td class="fw-semibold">{{ $worker->name }}</td>
                            <td>
                                <div class="d-flex gap-3">
                                    @foreach(['present', 'absent', 'late', 'half_day'] as $status)
                                        <div class="form-check status-{{ $status }}">
                                            <input class="form-check-input status-radio" type="radio"
                                                   name="workers[{{ $worker->id }}][status]"
                                                   value="{{ $status }}"
                                                   id="status_{{ $worker->id }}_{{ $status }}"
                                                   data-worker="{{ $worker->id }}"
                                                   {{ $currentStatus === $status ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_{{ $worker->id }}_{{ $status }}">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <input type="time" name="workers[{{ $worker->id }}][check_in]"
                                       class="form-control form-control-sm"
                                       value="{{ $existing?->check_in ? \Carbon\Carbon::parse($existing->check_in)->format('H:i') : '' }}">
                            </td>
                            <td>
                                <input type="time" name="workers[{{ $worker->id }}][check_out]"
                                       class="form-control form-control-sm"
                                       value="{{ $existing?->check_out ? \Carbon\Carbon::parse($existing->check_out)->format('H:i') : '' }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>

            <div class="p-3 border-top bg-light text-end">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check2-all me-1"></i> Save Attendance
                </button>
            </div>
        </form>
    </div>
@elseif($selectedFloor && $workers->count() === 0)
    <div class="text-center py-5 text-muted">
        <i class="bi bi-person-x" style="font-size: 3rem;"></i>
        <p class="mt-2">No workers assigned to this floor. <a href="{{ route('workers.create') }}">Add workers</a> first.</p>
    </div>
@endif

<script>
    function markAll(status) {
        document.querySelectorAll(`input.status-radio[value="${status}"]`).forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endsection

@extends('layouts.companyadmin')
@section('title', 'Mark Attendance')

@section('content')
<div class="table-card p-4 mb-4">
    <form method="GET" action="{{ route('companyadmin.attendance.mark') }}" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label fw-semibold">Select Floor</label>
            <select name="floor_id" class="form-select" required>
                <option value="">-- Choose Floor --</option>
                @foreach($floors as $f)
                    <option value="{{ $f->id }}" {{ ($selectedFloor && $selectedFloor->id == $f->id) ? 'selected' : '' }}>{{ $f->name }} (#{{ $f->floor_number }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}">
        </div>
        <div class="col-6 col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Load Workers</button>
        </div>
    </form>
</div>

@if($selectedFloor && $workers->count() > 0)
    <div class="table-card">
        <div class="p-3 border-bottom bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $selectedFloor->name }}</h6>
                    <small class="text-muted">{{ $workers->count() }} workers &middot; {{ \Carbon\Carbon::parse($date)->format('d M Y, l') }}</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="markAll('present')">All Present</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="markAll('absent')">All Absent</button>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('companyadmin.attendance.store') }}" class="attendance-form">
            @csrf
            <input type="hidden" name="floor_id" value="{{ $selectedFloor->id }}">
            <input type="hidden" name="date" value="{{ $date }}">
            <div class="table-responsive"><table class="table table-hover mb-0">
                <thead><tr><th width="60">#</th><th>Emp ID</th><th>Name</th><th>Status</th><th width="130">In</th><th width="130">Out</th></tr></thead>
                <tbody>
                    @foreach($workers as $i => $w)
                        @php $ex = $w->attendances->first(); $cs = $ex ? $ex->status : 'present'; @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td><td><code>{{ $w->employee_id }}</code></td><td class="fw-semibold">{{ $w->name }}</td>
                            <td>
                                <div class="d-flex gap-3">
                                    @foreach(['present','absent','late','half_day'] as $s)
                                        <div class="form-check status-{{ $s }}">
                                            <input class="form-check-input status-radio" type="radio" name="workers[{{ $w->id }}][status]" value="{{ $s }}" id="s_{{ $w->id }}_{{ $s }}" {{ $cs === $s ? 'checked' : '' }}>
                                            <label class="form-check-label" for="s_{{ $w->id }}_{{ $s }}">{{ ucfirst(str_replace('_',' ',$s)) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td><input type="time" name="workers[{{ $w->id }}][check_in]" class="form-control form-control-sm" value="{{ $ex?->check_in ? \Carbon\Carbon::parse($ex->check_in)->format('H:i') : '' }}"></td>
                            <td><input type="time" name="workers[{{ $w->id }}][check_out]" class="form-control form-control-sm" value="{{ $ex?->check_out ? \Carbon\Carbon::parse($ex->check_out)->format('H:i') : '' }}"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
            <div class="p-3 border-top bg-light text-end">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check2-all me-1"></i> Save Attendance</button>
            </div>
        </form>
    </div>
@elseif($selectedFloor && $workers->count() === 0)
    <div class="text-center py-5 text-muted"><i class="bi bi-person-x" style="font-size: 3rem;"></i><p class="mt-2">No workers on this floor.</p></div>
@endif
<script>function markAll(s){document.querySelectorAll(`input.status-radio[value="${s}"]`).forEach(r=>r.checked=true);}</script>
@endsection

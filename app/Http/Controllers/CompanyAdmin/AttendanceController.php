<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Floor;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    private function factoryId()
    {
        return auth()->user()->factory_id;
    }

    private function companyFloorIds()
    {
        return Floor::where('factory_id', $this->factoryId())->pluck('id');
    }

    public function markForm(Request $request)
    {
        $floors = Floor::where('factory_id', $this->factoryId())->get();
        $workers = collect();
        $selectedFloor = null;
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        if ($request->has('floor_id')) {
            $selectedFloor = Floor::where('factory_id', $this->factoryId())->find($request->floor_id);
            if ($selectedFloor) {
                $workers = Worker::where('floor_id', $selectedFloor->id)
                    ->with(['attendances' => fn($q) => $q->whereDate('date', $date)])
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('companyadmin.attendance.mark', compact('floors', 'workers', 'selectedFloor', 'date'));
    }

    public function store(Request $request)
    {
        $floorIds = $this->companyFloorIds();

        $request->validate([
            'floor_id' => ['required', 'exists:floors,id', function ($attr, $val, $fail) use ($floorIds) {
                if (!$floorIds->contains($val)) $fail('Invalid floor.');
            }],
            'date' => 'required|date',
            'workers' => 'required|array',
            'workers.*.status' => 'required|in:present,absent,late,half_day',
            'workers.*.check_in' => 'nullable',
            'workers.*.check_out' => 'nullable',
        ]);

        foreach ($request->workers as $workerId => $data) {
            Attendance::updateOrCreate(
                ['worker_id' => $workerId, 'date' => $request->date],
                [
                    'floor_id' => $request->floor_id,
                    'status' => $data['status'],
                    'check_in' => $data['check_in'] ?? null,
                    'check_out' => $data['check_out'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('companyadmin.attendance.mark', ['floor_id' => $request->floor_id, 'date' => $request->date])
            ->with('success', 'Attendance saved successfully.');
    }

    public function importForm()
    {
        return view('companyadmin.attendance.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'date' => 'required|date',
        ]);

        $floorIds = $this->companyFloorIds();
        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($handle);

        $imported = 0;
        $skipped = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;
            $data = array_combine($header, $row);

            $employeeId = trim($data['employee_id'] ?? '');
            $status = trim($data['status'] ?? '');
            $checkIn = trim($data['check_in'] ?? '') ?: null;
            $checkOut = trim($data['check_out'] ?? '') ?: null;

            if (!in_array($status, ['present', 'absent', 'late', 'half_day'])) {
                $errors[] = "Invalid status '{$status}' for {$employeeId}";
                $skipped++;
                continue;
            }

            $worker = Worker::where('employee_id', $employeeId)
                ->whereIn('floor_id', $floorIds)
                ->first();

            if (!$worker) {
                $errors[] = "Employee '{$employeeId}' not found in your company";
                $skipped++;
                continue;
            }

            Attendance::updateOrCreate(
                ['worker_id' => $worker->id, 'date' => $request->date],
                ['floor_id' => $worker->floor_id, 'status' => $status, 'check_in' => $checkIn, 'check_out' => $checkOut]
            );
            $imported++;
        }

        fclose($handle);

        $message = "{$imported} records imported.";
        if ($skipped > 0) $message .= " {$skipped} skipped.";

        return redirect()->route('companyadmin.attendance.import')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    public function report(Request $request)
    {
        $floorIds = $this->companyFloorIds();
        $floors = Floor::where('factory_id', $this->factoryId())->get();
        $attendances = collect();
        $summary = null;

        if ($request->has('date_from')) {
            $query = Attendance::with('worker.floor')
                ->whereIn('floor_id', $floorIds)
                ->whereBetween('date', [$request->date_from, $request->date_to ?? $request->date_from]);

            if ($request->filled('floor_id')) {
                $query->where('floor_id', $request->floor_id);
            }

            $attendances = $query->orderBy('date', 'desc')->get();

            $summary = [
                'total' => $attendances->count(),
                'present' => $attendances->where('status', 'present')->count(),
                'absent' => $attendances->where('status', 'absent')->count(),
                'late' => $attendances->where('status', 'late')->count(),
                'half_day' => $attendances->where('status', 'half_day')->count(),
            ];
        }

        return view('companyadmin.attendance.report', compact('floors', 'attendances', 'summary'));
    }

    public function export(Request $request)
    {
        $request->validate(['date_from' => 'required|date']);
        $floorIds = $this->companyFloorIds();

        $query = Attendance::with('worker.floor')
            ->whereIn('floor_id', $floorIds)
            ->whereBetween('date', [$request->date_from, $request->date_to ?? $request->date_from]);

        if ($request->filled('floor_id')) {
            $query->where('floor_id', $request->floor_id);
        }

        $attendances = $query->orderBy('date', 'desc')->get();
        $filename = 'attendance_' . $request->date_from . '.csv';

        return new StreamedResponse(function () use ($attendances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Employee ID', 'Worker', 'Phone', 'Floor', 'Status', 'Check In', 'Check Out']);

            foreach ($attendances as $att) {
                fputcsv($handle, [
                    $att->date->format('d-m-Y'),
                    $att->worker->employee_id,
                    $att->worker->name,
                    $att->worker->phone ?? '',
                    $att->worker->floor->name,
                    ucfirst(str_replace('_', ' ', $att->status)),
                    $att->check_in ? Carbon::parse($att->check_in)->format('h:i A') : '',
                    $att->check_out ? Carbon::parse($att->check_out)->format('h:i A') : '',
                ]);
            }
            fclose($handle);
        }, 200, ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Factory;
use App\Models\Floor;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    public function markForm(Request $request)
    {
        $floors = Floor::with('factory')->get();
        $workers = collect();
        $selectedFloor = null;
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        if ($request->has('floor_id')) {
            $selectedFloor = Floor::with('factory')->find($request->floor_id);
            if ($selectedFloor) {
                $workers = Worker::where('floor_id', $selectedFloor->id)
                    ->with(['attendances' => function ($q) use ($date) {
                        $q->whereDate('date', $date);
                    }])
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('attendance.mark', compact('floors', 'workers', 'selectedFloor', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'date' => 'required|date',
            'workers' => 'required|array',
            'workers.*.status' => 'required|in:present,absent,late,half_day',
            'workers.*.check_in' => 'nullable',
            'workers.*.check_out' => 'nullable',
        ]);

        $floorId = $request->floor_id;
        $date = $request->date;

        foreach ($request->workers as $workerId => $data) {
            Attendance::updateOrCreate(
                ['worker_id' => $workerId, 'date' => $date],
                [
                    'floor_id' => $floorId,
                    'status' => $data['status'],
                    'check_in' => $data['check_in'] ?? null,
                    'check_out' => $data['check_out'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('attendance.mark', ['floor_id' => $floorId, 'date' => $date])
            ->with('success', 'Attendance saved successfully.');
    }

    public function importForm()
    {
        return view('attendance.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'date' => 'required|date',
        ]);

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
                $errors[] = "Row skipped: Invalid status '{$status}' for {$employeeId}";
                $skipped++;
                continue;
            }

            $worker = Worker::where('employee_id', $employeeId)->first();
            if (!$worker) {
                $errors[] = "Row skipped: Employee ID '{$employeeId}' not found";
                $skipped++;
                continue;
            }

            Attendance::updateOrCreate(
                ['worker_id' => $worker->id, 'date' => $request->date],
                [
                    'floor_id' => $worker->floor_id,
                    'status' => $status,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                ]
            );
            $imported++;
        }

        fclose($handle);

        $message = "{$imported} records imported successfully.";
        if ($skipped > 0) {
            $message .= " {$skipped} rows skipped.";
        }

        return redirect()->route('attendance.import')
            ->with('success', $message)
            ->with('import_errors', $errors);
    }

    public function report(Request $request)
    {
        $factories = Factory::all();
        $floors = Floor::with('factory')->get();
        $attendances = collect();
        $summary = null;

        if ($request->has('date_from')) {
            $query = Attendance::with('worker.floor.factory')
                ->whereBetween('date', [$request->date_from, $request->date_to ?? $request->date_from]);

            if ($request->filled('floor_id')) {
                $query->where('floor_id', $request->floor_id);
            }

            if ($request->filled('factory_id')) {
                $query->whereHas('floor', function ($q) use ($request) {
                    $q->where('factory_id', $request->factory_id);
                });
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

        return view('attendance.report', compact('factories', 'floors', 'attendances', 'summary'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $attendances = Attendance::with('worker.floor.factory')
            ->whereDate('date', $date)
            ->orderBy('floor_id')
            ->get();

        $filename = 'attendance_' . $date . '.csv';

        $response = new StreamedResponse(function () use ($attendances, $date) {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, [
                'Date',
                'Employee ID',
                'Worker Name',
                'Floor',
                'Factory',
                'Status',
                'Check In',
                'Check Out',
            ]);

            foreach ($attendances as $att) {
                fputcsv($handle, [
                    Carbon::parse($att->date)->format('d-m-Y'),
                    $att->worker->employee_id,
                    $att->worker->name,
                    $att->worker->floor->name,
                    $att->worker->floor->factory->name,
                    ucfirst(str_replace('_', ' ', $att->status)),
                    $att->check_in ? Carbon::parse($att->check_in)->format('h:i A') : '',
                    $att->check_out ? Carbon::parse($att->check_out)->format('h:i A') : '',
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    public function exportReport(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to ?? $dateFrom;

        $query = Attendance::with('worker.floor.factory')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date', 'desc');

        if ($request->filled('floor_id')) {
            $query->where('floor_id', $request->floor_id);
        }

        if ($request->filled('factory_id')) {
            $query->whereHas('floor', function ($q) use ($request) {
                $q->where('factory_id', $request->factory_id);
            });
        }

        $attendances = $query->get();
        $filename = 'attendance_report_' . $dateFrom . '_to_' . $dateTo . '.csv';

        $response = new StreamedResponse(function () use ($attendances) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date',
                'Employee ID',
                'Worker Name',
                'Phone',
                'Floor',
                'Factory',
                'Status',
                'Check In',
                'Check Out',
            ]);

            foreach ($attendances as $att) {
                fputcsv($handle, [
                    Carbon::parse($att->date)->format('d-m-Y'),
                    $att->worker->employee_id,
                    $att->worker->name,
                    $att->worker->phone ?? '',
                    $att->worker->floor->name,
                    $att->worker->floor->factory->name,
                    ucfirst(str_replace('_', ' ', $att->status)),
                    $att->check_in ? Carbon::parse($att->check_in)->format('h:i A') : '',
                    $att->check_out ? Carbon::parse($att->check_out)->format('h:i A') : '',
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}

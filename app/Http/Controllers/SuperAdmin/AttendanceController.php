<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Factory;
use App\Models\Floor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
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
                $query->whereHas('floor', fn($q) => $q->where('factory_id', $request->factory_id));
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

        return view('superadmin.attendance.report', compact('factories', 'floors', 'attendances', 'summary'));
    }

    public function export(Request $request)
    {
        $request->validate(['date_from' => 'required|date']);

        $query = Attendance::with('worker.floor.factory')
            ->whereBetween('date', [$request->date_from, $request->date_to ?? $request->date_from]);

        if ($request->filled('floor_id')) {
            $query->where('floor_id', $request->floor_id);
        }
        if ($request->filled('factory_id')) {
            $query->whereHas('floor', fn($q) => $q->where('factory_id', $request->factory_id));
        }

        $attendances = $query->orderBy('date', 'desc')->get();
        $filename = 'attendance_report_' . $request->date_from . '.csv';

        return new StreamedResponse(function () use ($attendances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Employee ID', 'Worker', 'Phone', 'Floor', 'Factory', 'Status', 'Check In', 'Check Out']);

            foreach ($attendances as $att) {
                fputcsv($handle, [
                    $att->date->format('d-m-Y'),
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
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}

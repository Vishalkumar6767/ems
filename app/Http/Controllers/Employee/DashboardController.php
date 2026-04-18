<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $worker = $user->worker;

        if (!$worker) {
            return view('employee.no-profile');
        }

        $worker->load('floor.factory');
        $today = Carbon::today();

        // Today's attendance
        $todayAttendance = Attendance::where('worker_id', $worker->id)
            ->whereDate('date', $today)
            ->first();

        // This month stats
        $thisMonth = Attendance::where('worker_id', $worker->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        $monthStats = [
            'present' => $thisMonth->where('status', 'present')->count(),
            'absent' => $thisMonth->where('status', 'absent')->count(),
            'late' => $thisMonth->where('status', 'late')->count(),
            'half_day' => $thisMonth->where('status', 'half_day')->count(),
            'total_days' => $thisMonth->count(),
        ];

        // Last 7 days
        $recentAttendance = Attendance::where('worker_id', $worker->id)
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        return view('employee.dashboard', compact('worker', 'todayAttendance', 'monthStats', 'recentAttendance'));
    }

    public function attendance()
    {
        $user = auth()->user();
        $worker = $user->worker;

        if (!$worker) {
            return view('employee.no-profile');
        }

        $worker->load('floor.factory');

        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $attendances = Attendance::where('worker_id', $worker->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'half_day' => $attendances->where('status', 'half_day')->count(),
            'total' => $attendances->count(),
        ];

        return view('employee.attendance', compact('worker', 'attendances', 'summary', 'month', 'year'));
    }
}

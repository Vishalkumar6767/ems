<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Floor;
use App\Models\Worker;
use App\Models\Factory;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $floors = Floor::with('factory')->withCount('workers')->get();

        $floorStats = $floors->map(function ($floor) use ($today) {
            $todayAttendance = Attendance::where('floor_id', $floor->id)
                ->whereDate('date', $today)
                ->get();

            return [
                'floor' => $floor,
                'total_workers' => $floor->workers_count,
                'present' => $todayAttendance->where('status', 'present')->count(),
                'absent' => $todayAttendance->where('status', 'absent')->count(),
                'late' => $todayAttendance->where('status', 'late')->count(),
                'half_day' => $todayAttendance->where('status', 'half_day')->count(),
                'not_marked' => $floor->workers_count - $todayAttendance->count(),
            ];
        });

        $totalWorkers = Worker::count();
        $totalPresent = Attendance::whereDate('date', $today)->where('status', 'present')->count();
        $totalAbsent = Attendance::whereDate('date', $today)->where('status', 'absent')->count();
        $totalLate = Attendance::whereDate('date', $today)->where('status', 'late')->count();
        $totalFactories = Factory::count();

        return view('dashboard.index', compact(
            'floorStats', 'totalWorkers', 'totalPresent', 'totalAbsent', 'totalLate', 'totalFactories', 'today'
        ));
    }
}

<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Floor;
use App\Models\Worker;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $factory = $user->factory;
        $today = Carbon::today();

        $floorIds = Floor::where('factory_id', $factory->id)->pluck('id');

        $totalWorkers = Worker::whereIn('floor_id', $floorIds)->count();
        $todayAtt = Attendance::whereIn('floor_id', $floorIds)->whereDate('date', $today)->get();

        $stats = [
            'total_workers' => $totalWorkers,
            'total_floors' => $floorIds->count(),
            'present' => $todayAtt->where('status', 'present')->count(),
            'absent' => $todayAtt->where('status', 'absent')->count(),
            'late' => $todayAtt->where('status', 'late')->count(),
            'half_day' => $todayAtt->where('status', 'half_day')->count(),
        ];

        $floors = Floor::where('factory_id', $factory->id)->withCount('workers')->get();

        $floorStats = $floors->map(function ($floor) use ($today) {
            $att = Attendance::where('floor_id', $floor->id)->whereDate('date', $today)->get();
            return [
                'floor' => $floor,
                'total_workers' => $floor->workers_count,
                'present' => $att->where('status', 'present')->count(),
                'absent' => $att->where('status', 'absent')->count(),
                'late' => $att->where('status', 'late')->count(),
                'half_day' => $att->where('status', 'half_day')->count(),
                'not_marked' => $floor->workers_count - $att->count(),
            ];
        });

        return view('companyadmin.dashboard', compact('factory', 'stats', 'floorStats', 'today'));
    }
}

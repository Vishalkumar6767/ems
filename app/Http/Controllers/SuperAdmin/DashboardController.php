<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Factory;
use App\Models\Floor;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_factories' => Factory::count(),
            'total_floors' => Floor::count(),
            'total_workers' => Worker::count(),
            'total_admins' => User::where('role', 'company_admin')->count(),
            'present_today' => Attendance::whereDate('date', $today)->where('status', 'present')->count(),
            'absent_today' => Attendance::whereDate('date', $today)->where('status', 'absent')->count(),
            'late_today' => Attendance::whereDate('date', $today)->where('status', 'late')->count(),
            'half_day_today' => Attendance::whereDate('date', $today)->where('status', 'half_day')->count(),
        ];

        $factories = Factory::withCount(['floors', 'floors as workers_count' => function ($q) {
            $q->join('workers', 'floors.id', '=', 'workers.floor_id')
              ->select(\DB::raw('count(workers.id)'));
        }])->latest()->get();

        // Per-factory today's attendance
        $factoryStats = $factories->map(function ($factory) use ($today) {
            $floorIds = $factory->floors()->pluck('id');
            $todayAtt = Attendance::whereIn('floor_id', $floorIds)->whereDate('date', $today)->get();
            $totalWorkers = Worker::whereIn('floor_id', $floorIds)->count();

            return [
                'factory' => $factory,
                'total_workers' => $totalWorkers,
                'present' => $todayAtt->where('status', 'present')->count(),
                'absent' => $todayAtt->where('status', 'absent')->count(),
                'late' => $todayAtt->where('status', 'late')->count(),
                'half_day' => $todayAtt->where('status', 'half_day')->count(),
            ];
        });

        return view('superadmin.dashboard', compact('stats', 'factoryStats'));
    }
}

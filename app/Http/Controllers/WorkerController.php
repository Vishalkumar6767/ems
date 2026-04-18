<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Floor;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::with('floor.factory')->latest()->get();
        return view('workers.index', compact('workers'));
    }

    public function create()
    {
        $floors = Floor::with('factory')->get();
        return view('workers.create', compact('floors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'name' => 'required|max:255',
            'employee_id' => 'required|unique:workers,employee_id',
            'phone' => 'nullable|max:20',
        ]);

        Worker::create($request->only('floor_id', 'name', 'employee_id', 'phone'));
        return redirect()->route('workers.index')->with('success', 'Worker added successfully.');
    }

    public function show(Worker $worker)
    {
        $worker->load('floor.factory');
        $recentAttendance = Attendance::where('worker_id', $worker->id)
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        $thisMonth = Attendance::where('worker_id', $worker->id)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->get();

        $monthStats = [
            'present' => $thisMonth->where('status', 'present')->count(),
            'absent' => $thisMonth->where('status', 'absent')->count(),
            'late' => $thisMonth->where('status', 'late')->count(),
            'half_day' => $thisMonth->where('status', 'half_day')->count(),
        ];

        return view('workers.show', compact('worker', 'recentAttendance', 'monthStats'));
    }

    public function edit(Worker $worker)
    {
        $floors = Floor::with('factory')->get();
        return view('workers.edit', compact('worker', 'floors'));
    }

    public function update(Request $request, Worker $worker)
    {
        $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'name' => 'required|max:255',
            'employee_id' => 'required|unique:workers,employee_id,' . $worker->id,
            'phone' => 'nullable|max:20',
        ]);

        $worker->update($request->only('floor_id', 'name', 'employee_id', 'phone'));
        return redirect()->route('workers.index')->with('success', 'Worker updated successfully.');
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return redirect()->route('workers.index')->with('success', 'Worker deleted successfully.');
    }
}

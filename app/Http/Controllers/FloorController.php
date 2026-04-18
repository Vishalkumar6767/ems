<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Factory;
use App\Models\Floor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index()
    {
        $floors = Floor::with('factory')->withCount('workers')->latest()->get();
        return view('floors.index', compact('floors'));
    }

    public function create()
    {
        $factories = Factory::all();
        return view('floors.create', compact('factories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'factory_id' => 'required|exists:factories,id',
            'name' => 'required|max:255',
            'floor_number' => 'required|integer|min:0',
        ]);

        Floor::create($request->only('factory_id', 'name', 'floor_number'));
        return redirect()->route('floors.index')->with('success', 'Floor created successfully.');
    }

    public function show(Floor $floor)
    {
        $floor->load(['factory', 'workers']);
        $today = Carbon::today();
        $todayAttendance = Attendance::where('floor_id', $floor->id)->whereDate('date', $today)->get();
        $stats = [
            'total' => $floor->workers->count(),
            'present' => $todayAttendance->where('status', 'present')->count(),
            'absent' => $todayAttendance->where('status', 'absent')->count(),
            'late' => $todayAttendance->where('status', 'late')->count(),
            'half_day' => $todayAttendance->where('status', 'half_day')->count(),
        ];
        return view('floors.show', compact('floor', 'stats'));
    }

    public function edit(Floor $floor)
    {
        $factories = Factory::all();
        return view('floors.edit', compact('floor', 'factories'));
    }

    public function update(Request $request, Floor $floor)
    {
        $request->validate([
            'factory_id' => 'required|exists:factories,id',
            'name' => 'required|max:255',
            'floor_number' => 'required|integer|min:0',
        ]);

        $floor->update($request->only('factory_id', 'name', 'floor_number'));
        return redirect()->route('floors.index')->with('success', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor)
    {
        $floor->delete();
        return redirect()->route('floors.index')->with('success', 'Floor deleted successfully.');
    }
}

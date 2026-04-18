<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Floor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    private function factoryId()
    {
        return auth()->user()->factory_id;
    }

    public function index()
    {
        $floors = Floor::where('factory_id', $this->factoryId())
            ->withCount('workers')
            ->latest()
            ->get();

        return view('companyadmin.floors.index', compact('floors'));
    }

    public function create()
    {
        return view('companyadmin.floors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'floor_number' => 'required|integer|min:0',
        ]);

        Floor::create([
            'factory_id' => $this->factoryId(),
            'name' => $request->name,
            'floor_number' => $request->floor_number,
        ]);

        return redirect()->route('companyadmin.floors.index')->with('success', 'Floor created successfully.');
    }

    public function show(Floor $floor)
    {
        if ($floor->factory_id !== $this->factoryId()) {
            abort(403);
        }

        $floor->load('workers');
        $today = Carbon::today();
        $todayAtt = Attendance::where('floor_id', $floor->id)->whereDate('date', $today)->get();

        $stats = [
            'total' => $floor->workers->count(),
            'present' => $todayAtt->where('status', 'present')->count(),
            'absent' => $todayAtt->where('status', 'absent')->count(),
            'late' => $todayAtt->where('status', 'late')->count(),
            'half_day' => $todayAtt->where('status', 'half_day')->count(),
        ];

        return view('companyadmin.floors.show', compact('floor', 'stats'));
    }

    public function edit(Floor $floor)
    {
        if ($floor->factory_id !== $this->factoryId()) {
            abort(403);
        }

        return view('companyadmin.floors.edit', compact('floor'));
    }

    public function update(Request $request, Floor $floor)
    {
        if ($floor->factory_id !== $this->factoryId()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|max:255',
            'floor_number' => 'required|integer|min:0',
        ]);

        $floor->update($request->only('name', 'floor_number'));
        return redirect()->route('companyadmin.floors.index')->with('success', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor)
    {
        if ($floor->factory_id !== $this->factoryId()) {
            abort(403);
        }

        $floor->delete();
        return redirect()->route('companyadmin.floors.index')->with('success', 'Floor deleted successfully.');
    }
}

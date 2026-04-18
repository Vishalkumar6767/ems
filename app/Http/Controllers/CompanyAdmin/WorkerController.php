<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Floor;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    private function factoryId()
    {
        return auth()->user()->factory_id;
    }

    private function companyFloorIds()
    {
        return Floor::where('factory_id', $this->factoryId())->pluck('id');
    }

    public function index()
    {
        $workers = Worker::with('floor')
            ->whereIn('floor_id', $this->companyFloorIds())
            ->latest()
            ->get();

        return view('companyadmin.workers.index', compact('workers'));
    }

    public function create()
    {
        $floors = Floor::where('factory_id', $this->factoryId())->get();
        return view('companyadmin.workers.create', compact('floors'));
    }

    public function store(Request $request)
    {
        $floorIds = $this->companyFloorIds();

        $request->validate([
            'floor_id' => ['required', 'exists:floors,id', function ($attr, $val, $fail) use ($floorIds) {
                if (!$floorIds->contains($val)) $fail('Invalid floor.');
            }],
            'name' => 'required|max:255',
            'employee_id' => 'required|unique:workers,employee_id',
            'phone' => 'nullable|max:20',
            'designation' => 'nullable|max:255',
            'create_login' => 'nullable|boolean',
            'email' => 'nullable|required_if:create_login,1|email|unique:users,email',
            'password' => 'nullable|required_if:create_login,1|min:6',
        ]);

        $userId = null;

        if ($request->boolean('create_login')) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'employee',
                'factory_id' => $this->factoryId(),
            ]);
            $userId = $user->id;
        }

        Worker::create([
            'floor_id' => $request->floor_id,
            'user_id' => $userId,
            'name' => $request->name,
            'employee_id' => $request->employee_id,
            'phone' => $request->phone,
            'designation' => $request->designation,
        ]);

        return redirect()->route('companyadmin.workers.index')->with('success', 'Worker added successfully.');
    }

    public function show(Worker $worker)
    {
        if (!$this->companyFloorIds()->contains($worker->floor_id)) {
            abort(403);
        }

        $worker->load('floor', 'user');
        $recentAttendance = $worker->attendances()->orderBy('date', 'desc')->limit(30)->get();

        $thisMonth = $worker->attendances()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        $monthStats = [
            'present' => $thisMonth->where('status', 'present')->count(),
            'absent' => $thisMonth->where('status', 'absent')->count(),
            'late' => $thisMonth->where('status', 'late')->count(),
            'half_day' => $thisMonth->where('status', 'half_day')->count(),
        ];

        return view('companyadmin.workers.show', compact('worker', 'recentAttendance', 'monthStats'));
    }

    public function edit(Worker $worker)
    {
        if (!$this->companyFloorIds()->contains($worker->floor_id)) {
            abort(403);
        }

        $floors = Floor::where('factory_id', $this->factoryId())->get();
        return view('companyadmin.workers.edit', compact('worker', 'floors'));
    }

    public function update(Request $request, Worker $worker)
    {
        if (!$this->companyFloorIds()->contains($worker->floor_id)) {
            abort(403);
        }

        $floorIds = $this->companyFloorIds();

        $request->validate([
            'floor_id' => ['required', 'exists:floors,id', function ($attr, $val, $fail) use ($floorIds) {
                if (!$floorIds->contains($val)) $fail('Invalid floor.');
            }],
            'name' => 'required|max:255',
            'employee_id' => 'required|unique:workers,employee_id,' . $worker->id,
            'phone' => 'nullable|max:20',
            'designation' => 'nullable|max:255',
        ]);

        $worker->update($request->only('floor_id', 'name', 'employee_id', 'phone', 'designation'));
        return redirect()->route('companyadmin.workers.index')->with('success', 'Worker updated successfully.');
    }

    public function destroy(Worker $worker)
    {
        if (!$this->companyFloorIds()->contains($worker->floor_id)) {
            abort(403);
        }

        $worker->delete();
        return redirect()->route('companyadmin.workers.index')->with('success', 'Worker deleted successfully.');
    }
}

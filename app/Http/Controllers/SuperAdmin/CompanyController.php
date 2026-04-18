<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Factory;
use App\Models\Floor;
use App\Models\User;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $factories = Factory::withCount('floors')->latest()->get();
        return view('superadmin.companies.index', compact('factories'));
    }

    public function create()
    {
        return view('superadmin.companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'nullable',
        ]);

        Factory::create($request->only('name', 'address'));
        return redirect()->route('superadmin.companies.index')->with('success', 'Company created successfully.');
    }

    public function show(Factory $company)
    {
        $company->load(['floors' => fn($q) => $q->withCount('workers')]);
        $totalWorkers = Worker::whereIn('floor_id', $company->floors->pluck('id'))->count();
        $admins = User::where('factory_id', $company->id)->where('role', 'company_admin')->get();

        $today = Carbon::today();
        $floorIds = $company->floors->pluck('id');
        $todayAtt = Attendance::whereIn('floor_id', $floorIds)->whereDate('date', $today)->get();

        $stats = [
            'present' => $todayAtt->where('status', 'present')->count(),
            'absent' => $todayAtt->where('status', 'absent')->count(),
            'late' => $todayAtt->where('status', 'late')->count(),
            'half_day' => $todayAtt->where('status', 'half_day')->count(),
        ];

        return view('superadmin.companies.show', compact('company', 'totalWorkers', 'admins', 'stats'));
    }

    public function edit(Factory $company)
    {
        return view('superadmin.companies.edit', compact('company'));
    }

    public function update(Request $request, Factory $company)
    {
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'nullable',
        ]);

        $company->update($request->only('name', 'address'));
        return redirect()->route('superadmin.companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Factory $company)
    {
        $company->delete();
        return redirect()->route('superadmin.companies.index')->with('success', 'Company deleted successfully.');
    }
}

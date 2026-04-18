<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('factory')->where('role', '!=', 'super_admin');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('factory_id')) {
            $query->where('factory_id', $request->factory_id);
        }

        $users = $query->latest()->get();
        $factories = Factory::all();

        return view('superadmin.users.index', compact('users', 'factories'));
    }

    public function create()
    {
        $factories = Factory::all();
        return view('superadmin.users.create', compact('factories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => ['required', Rule::in(['company_admin', 'employee'])],
            'factory_id' => 'required|exists:factories,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'factory_id' => $request->factory_id,
        ]);

        // If employee, optionally link to existing worker
        if ($request->role === 'employee' && $request->filled('worker_id')) {
            Worker::where('id', $request->worker_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('superadmin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $factories = Factory::all();
        return view('superadmin.users.edit', compact('user', 'factories'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['company_admin', 'employee'])],
            'factory_id' => 'required|exists:factories,id',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'factory_id' => $request->factory_id,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('superadmin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Cannot delete super admin.');
        }

        $user->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'User deleted successfully.');
    }

    public function workers(Request $request)
    {
        $query = Worker::with('floor.factory');

        if ($request->filled('factory_id')) {
            $query->whereHas('floor', fn($q) => $q->where('factory_id', $request->factory_id));
        }

        $workers = $query->latest()->get();
        $factories = Factory::all();

        return view('superadmin.workers.index', compact('workers', 'factories'));
    }

    public function showWorker(Worker $worker)
    {
        $worker->load('floor.factory', 'user');
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

        return view('superadmin.workers.show', compact('worker', 'recentAttendance', 'monthStats'));
    }
}

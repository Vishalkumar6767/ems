<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use Illuminate\Http\Request;

class FactoryController extends Controller
{
    public function index()
    {
        $factories = Factory::withCount('floors')->latest()->get();
        return view('factories.index', compact('factories'));
    }

    public function create()
    {
        return view('factories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'nullable',
        ]);

        Factory::create($request->only('name', 'address'));
        return redirect()->route('factories.index')->with('success', 'Factory created successfully.');
    }

    public function show(Factory $factory)
    {
        $factory->load(['floors' => function ($q) {
            $q->withCount('workers');
        }]);
        $totalWorkers = $factory->floors->sum('workers_count');
        return view('factories.show', compact('factory', 'totalWorkers'));
    }

    public function edit(Factory $factory)
    {
        return view('factories.edit', compact('factory'));
    }

    public function update(Request $request, Factory $factory)
    {
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'nullable',
        ]);

        $factory->update($request->only('name', 'address'));
        return redirect()->route('factories.index')->with('success', 'Factory updated successfully.');
    }

    public function destroy(Factory $factory)
    {
        $factory->delete();
        return redirect()->route('factories.index')->with('success', 'Factory deleted successfully.');
    }
}

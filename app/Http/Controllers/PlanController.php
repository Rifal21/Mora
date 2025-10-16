<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->get();
        return view('main.billing.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('main.billing.plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'description' => 'nullable',
            'features' => 'nullable|string',
        ]);

        $data['features'] = $data['features']
            ? json_encode(array_map('trim', explode(',', $data['features'])))
            : json_encode([]);

        Plan::create($data);
        return redirect()->route('plans.index')->with('success', 'Plan berhasil ditambahkan.');
    }

    public function edit(Plan $plan)
    {
        return view('main.billing.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'description' => 'nullable',
            'features' => 'nullable|string',
        ]);

        $data['features'] = $data['features']
            ? json_encode(array_map('trim', explode(',', $data['features'])))
            : json_encode([]);

        $plan->update($data);
        return redirect()->route('plans.index')->with('success', 'Plan berhasil diperbarui.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan berhasil dihapus.');
    }
}

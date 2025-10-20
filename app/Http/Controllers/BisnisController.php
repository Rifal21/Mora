<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBisnisRequest;
use App\Http\Requests\UpdateBisnisRequest;
use App\Models\Bisnis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BisnisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bisnisList = Auth::user()->bisnis()->latest()->get();
        $bisnisAdminList = Bisnis::with('user')->latest()->paginate(5);

        return view('main.bisnis.index', compact('bisnisList', 'bisnisAdminList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(Auth::user()->profile->user_type == 'free' && Auth::user()->bisnis()->count() >= 1) {
            return redirect()->route('bisnis.index')->with('error', 'Upgrade ke akun Pro untuk menambahkan lebih banyak bisnis.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'qris' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'alamat', 'telepon', 'email', 'website']);
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(4);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }

        if ($request->hasFile('qris')) {
            $data['qris'] = $request->file('qris')->store('qris', 'public');
        }

        Bisnis::create($data);

        return redirect()->route('bisnis.index')->with('success', 'Bisnis berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bisnis $bisnis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bisnis $bisni)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bisnis $bisni)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'qris' => 'nullable|image|max:2048',
        ]);

        // Update logo
        if ($request->hasFile('logo')) {
            if ($bisni->logo && Storage::disk('public')->exists($bisni->logo)) {
                Storage::disk('public')->delete($bisni->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logo', 'public');
        }

        // Update QRIS
        if ($request->hasFile('qris')) {
            if ($bisni->qris && Storage::disk('public')->exists($bisni->qris)) {
                Storage::disk('public')->delete($bisni->qris);
            }
            $validated['qris'] = $request->file('qris')->store('qris', 'public');
        }

        $bisni->update($validated);

        return redirect()->route('bisnis.index')->with('success', 'Bisnis berhasil diperbarui!');
    }

    // Hapus Bisnis (Hard Delete)
    public function destroy(Bisnis $bisni)
    {
        // Hapus file logo dan QRIS jika ada
        if ($bisni->logo && Storage::disk('public')->exists($bisni->logo)) {
            Storage::disk('public')->delete($bisni->logo);
        }

        if ($bisni->qris && Storage::disk('public')->exists($bisni->qris)) {
            Storage::disk('public')->delete($bisni->qris);
        }

        $bisni->delete(); // Hard delete

        return redirect()->route('bisnis.index')->with('success', 'Bisnis berhasil dihapus!');
    }

    public function set(Request $request)
    {
        $request->validate([
            'bisnis_id' => 'required|exists:bisnis,id'
        ]);

        $bisnis = Bisnis::where('id', $request->bisnis_id)->first();
        session(['bisnis_id' => $bisnis->id]);
        session(['bisnis_qris' => $bisnis->qris]);
        session(['bisnis_name' => $bisnis->name]);
        session(['bisnis_logo' => $bisnis->logo]);
        session(['bisnis_alamat' => $bisnis->alamat]);
        session(['bisnis_telepon' => $bisnis->telepon]);
        return redirect()->back()->with('success', 'Bisnis berhasil diatur!');
    }
    
}

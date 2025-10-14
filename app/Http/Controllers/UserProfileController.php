<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $userProfile = $user->profile;
        return view('main.userProfile.index', compact('userProfile'));
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
    public function store(StoreUserProfileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserProfile $userProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'no_ktp' => 'nullable|string|max:20',
            'no_npwp' => 'nullable|string|max:20',
            'gender' => 'nullable|string|max:10',
            'place_of_birth' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpg,png|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $profile->fill($validated)->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserProfile $userProfile)
    {
        //
    }
}

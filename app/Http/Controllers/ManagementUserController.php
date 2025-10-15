<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ManagementUserController extends Controller
{
    public function index()
    {
        $users = User::with(['profile', 'role'])->latest()->get();
        $roles = Role::all();
        return view('main.user.index', compact('users', 'roles'));
    }
    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users',
            'email'         => 'required|email|max:255|unique:users',
            'password'      => 'required|string|min:6|confirmed',
            'role_id'       => 'nullable|exists:roles,id',
            'user_type'     => 'required|in:free,pro',
            'full_name'     => 'required|string|max:255',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan user
        $user = User::create([
            'id'        => Str::uuid(),
            'role_id'   => $request->role_id,
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'status'    => 'active',
        ]);

        // Upload avatar jika ada
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Simpan profil
        UserProfile::create([
            'id'            => Str::uuid(),
            'user_id'       => $user->id,
            'user_type'     => $request->user_type,
            'full_name'     => $request->full_name,
            'address'       => $request->address,
            'phone_number'  => $request->phone_number,
            'avatar'        => $avatarPath,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id'       => 'nullable|exists:roles,id',
            'password'      => 'nullable|string|min:6|confirmed',
            'user_type'     => 'required|in:free,pro',
            'full_name'     => 'required|string|max:255',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        dd($request->all());

        // Update user
        $user->update([
            'role_id'   => $request->role_id,
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'status'    => $request->status ?? $user->status,
            'password'  => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        // Handle avatar
        $avatarPath = $user->profile->avatar ?? null;
        if ($request->hasFile('avatar')) {
            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Update profile
        $user->profile->update([
            'user_type'     => $request->user_type,
            'full_name'     => $request->full_name,
            'address'       => $request->address,
            'phone_number'  => $request->phone_number,
            'avatar'        => $avatarPath,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete($user->profile->avatar);
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}

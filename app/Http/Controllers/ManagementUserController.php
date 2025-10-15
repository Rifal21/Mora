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
        // dd($request->all());
        $request->validate([
            'full_name'          => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users',
            'email'         => 'required|email|max:255|unique:users',
            'password'      => 'required|string|min:6',
        ]);

        // Simpan user
        $user = User::create([
            'role_id'   => Role::where('name', 'user')->first()->id,
            'name'      => $request->full_name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'status'    => 'active',
        ]);


        // Simpan profil
        UserProfile::create([
            'user_id'       => $user->id,
            'full_name'     => $user->name,
            'quota_ai'      => 100,
            'quota_trx'     => 50,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username'      => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id'       => 'nullable|exists:roles,id',
            'password'      => 'nullable|string|min:6|confirmed',
            'user_type'     => 'required|in:free,pro',
            'full_name'     => 'required|string|max:255',
        ]);

        // Update user
        $user->update([
            'role_id'   => $request->role_id,
            'name'      => $request->full_name,
            'username'  => $request->username,
            'email'     => $request->email,
            'status'    => $request->status ?? $user->status,
            'password'  => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);


        // Update profile
        $user->profile->update([
            'user_type'     => $request->user_type,
            'full_name'     => $request->full_name,
            'quota_ai'      => $request->quota_ai,
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

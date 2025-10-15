<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'place_of_birth' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:10'],
        ]);

        $role = Role::where('name', 'user')->first();

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role?->id,
        ]);

        $quota_ai = 100;

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name'      => $validated['name'],
                'phone_number'   => $validated['phone_number'] ?? null,
                'place_of_birth' => $validated['place_of_birth'] ?? null,
                'birth_date'     => $validated['birth_date'] ?? null,
                'gender'         => $validated['gender'] ?? null,
                'quota_ai'       => $quota_ai,
                'quota_trx' => 100
            ]
        );

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}

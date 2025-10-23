<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah ada
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $role = \App\Models\Role::where('name', 'user')->first();

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'username' => Str::slug($googleUser->getName()) . rand(100, 999),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(12)),
                    'role_id' => $role?->id,
                    'email_verified_at' => now(),
                ]);

                // Buat profil default
                $user->profile()->create([
                    'user_id' => $user->id,
                    'full_name' => $googleUser->getName(),
                    'quota_ai' => 20,
                    'quota_trx' => 20,
                ]);
            }

            Auth::login($user);

            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}

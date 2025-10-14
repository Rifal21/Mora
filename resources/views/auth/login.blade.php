<x-guest-layout>
    <div class=" flex items-start justify-center  relative  overflow-hidden px-6 sm:px-0">
        <!-- Background Glow -->
        <div class="absolute inset-0">
            <div class="absolute w-72 h-72 bg-pink-400/30 rounded-full blur-3xl top-10 left-10 animate-pulse"></div>
            <div class="absolute w-80 h-80 bg-indigo-400/30 rounded-full blur-3xl bottom-10 right-10 animate-pulse">
            </div>
        </div>

        <!-- Glass Card -->
        <div
            class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-2xl border border-white/20 rounded-3xl  p-8 text-white animate-fadeIn">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-white/20 p-3 rounded-full shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3v4M8 3v4m-5 4h18" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold tracking-tight drop-shadow-lg">Selamat Datang Kembali 👋</h1>
                <p class="text-sm text-gray-200 mt-1">Masuk untuk mengelola bisnis Anda</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-200" />
                    <input id="email"
                        class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white placeholder-gray-300 focus:border-indigo-400 focus:ring-indigo-400 focus:bg-white/20"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        placeholder="email@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-300" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-gray-200" />
                    <input id="password"
                        class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white placeholder-gray-300 focus:border-indigo-400 focus:ring-indigo-400 focus:bg-white/20"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-300" />
                </div>

                <!-- Remember me + Forgot password -->
                <div class="flex items-center justify-between text-sm">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded bg-white/20 border-white/30 text-indigo-400 focus:ring-indigo-400"
                            name="remember">
                        <span class="ml-2 text-gray-200">{{ __('Ingat saya') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-indigo-300 hover:text-indigo-100 font-medium transition">
                            {{ __('Lupa Password?') }}
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-2 bg-indigo-500 hover:bg-indigo-600 rounded-xl font-semibold text-white shadow-lg hover:shadow-indigo-400/50 transition-all duration-300 focus:ring-4 focus:ring-indigo-300 focus:outline-none">
                        {{ __('Masuk') }}
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-grow h-px bg-white/20"></div>
                <span class="px-3 text-sm text-gray-300">atau</span>
                <div class="flex-grow h-px bg-white/20"></div>
            </div>

            <!-- Register link -->
            <p class="text-center text-sm text-gray-200">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="text-indigo-300 hover:text-indigo-100 font-semibold transition">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>

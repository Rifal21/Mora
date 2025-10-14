<x-guest-layout>
    <div class="flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Glow -->
        <div class="absolute inset-0">
            <div class="absolute w-72 h-72 bg-pink-400/30 rounded-full blur-3xl top-10 left-10 animate-pulse"></div>
            <div class="absolute w-80 h-80 bg-indigo-400/30 rounded-full blur-3xl bottom-10 right-10 animate-pulse">
            </div>
        </div>

        <!-- Glass Card -->
        <div
            class="relative z-10 w-full max-w-2xl bg-white/10 backdrop-blur-2xl border border-white/20 rounded-3xl shadow-[0_8px_32px_0_rgba(31,38,135,0.37)] p-8 text-white animate-fadeIn">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-white/20 p-3 rounded-full shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold tracking-tight drop-shadow-lg">Buat Akun Baru ✨</h1>
                <p class="text-sm text-gray-200 mt-1">Daftar untuk mulai mengelola bisnis Anda</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Grid Layout -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-200" />
                        <input id="name"
                            class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white placeholder-gray-300 focus:border-indigo-400 focus:ring-indigo-400"
                            type="text" name="name" :value="old('name')" required autofocus
                            placeholder="Nama lengkap Anda" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Username -->
                    <div>
                        <x-input-label for="username" :value="__('Username')" class="text-gray-200" />
                        <input id="username"
                            class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white placeholder-gray-300 focus:border-indigo-400 focus:ring-indigo-400"
                            type="text" name="username" :value="old('username')" required
                            placeholder="Username unik" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-200" />
                        <input id="email"
                            class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white placeholder-gray-300 focus:border-indigo-400 focus:ring-indigo-400"
                            type="email" name="email" :value="old('email')" required
                            placeholder="email@contoh.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <x-input-label for="phone_number" :value="__('Nomor Telepon')" class="text-gray-200" />
                        <input id="phone_number"
                            class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white placeholder-gray-300 focus:border-indigo-400 focus:ring-indigo-400"
                            type="text" name="phone_number" :value="old('phone_number')" required
                            placeholder="08xxxxxxxxxx" />
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Place of Birth -->
                    <div>
                        <x-input-label for="place_of_birth" :value="__('Tempat Lahir')" class="text-gray-200" />
                        <input id="place_of_birth"
                            class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white placeholder-gray-300 focus:border-indigo-400 focus:ring-indigo-400"
                            type="text" name="place_of_birth" :value="old('place_of_birth')" required
                            placeholder="Contoh: Jakarta" />
                        <x-input-error :messages="$errors->get('place_of_birth')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <x-input-label for="birth_date" :value="__('Tanggal Lahir')" class="text-gray-200" />
                        <input id="birth_date"
                            class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white focus:border-indigo-400 focus:ring-indigo-400"
                            type="date" name="birth_date" :value="old('birth_date')" required />
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Gender -->
                    <div>
                        <x-input-label for="gender" :value="__('Jenis Kelamin')" class="text-gray-200" />
                        <select id="gender" name="gender"
                            class="block mt-1 w-full rounded-xl bg-white/10 border border-white/30 text-white 
           focus:border-indigo-400 focus:ring-indigo-400 appearance-none 
           [color-scheme:dark]">
                            <option value="" disabled selected class="bg-gray-800 text-gray-300">Pilih jenis
                                kelamin</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}
                                class="bg-gray-800 text-gray-100">Laki-laki</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}
                                class="bg-gray-800 text-gray-100">Perempuan</option>
                        </select>

                        <x-input-error :messages="$errors->get('gender')" class="mt-2 text-pink-300" />
                    </div>
                </div>

                <!-- Password (Full width) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="password" :value="__('Kata Sandi')" class="text-gray-200" />
                        <input id="password"
                            class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white placeholder-gray-300 focus:border-indigo-400 focus:ring-indigo-400"
                            type="password" name="password" required autocomplete="new-password"
                            placeholder="Minimal 8 karakter" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-300" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-gray-200" />
                        <input id="password_confirmation"
                            class="block mt-1 w-full rounded-xl bg-white/10 border-white/30 text-white placeholder-gray-300 focus:border-indigo-400 focus:ring-indigo-400"
                            type="password" name="password_confirmation" required autocomplete="new-password"
                            placeholder="Ulangi kata sandi Anda" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-pink-300" />
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-2 bg-indigo-500 hover:bg-indigo-600 rounded-xl font-semibold text-white shadow-lg hover:shadow-indigo-400/50 transition-all duration-300 focus:ring-4 focus:ring-indigo-300 focus:outline-none">
                        {{ __('Daftar Sekarang') }}
                    </button>
                </div>

                <!-- Divider -->
                <div class="flex items-center my-6">
                    <div class="flex-grow h-px bg-white/20"></div>
                    <span class="px-3 text-sm text-gray-300">atau</span>
                    <div class="flex-grow h-px bg-white/20"></div>
                </div>

                <!-- Login Link -->
                <p class="text-center text-sm text-gray-200">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                        class="text-indigo-300 hover:text-indigo-100 font-semibold transition">
                        Masuk di sini
                    </a>
                </p>
            </form>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</x-guest-layout>

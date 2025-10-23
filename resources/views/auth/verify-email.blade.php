<x-guest-layout>
    <div class="flex items-center justify-center p-6 relative overflow-hidden">

        <!-- Efek Cahaya -->
        <div class="absolute inset-0">
            <div class="absolute w-72 h-72 bg-indigo-400/30 rounded-full blur-3xl top-10 left-10 animate-pulse"></div>
            <div class="absolute w-80 h-80 bg-pink-400/30 rounded-full blur-3xl bottom-10 right-10 animate-pulse"></div>
        </div>

        <!-- Kartu Verifikasi -->
        <div
            class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-2xl border border-white/20 rounded-3xl shadow-lg p-8 text-white text-center animate-fadeIn">

            <!-- Icon Email -->
            <div class="flex justify-center mb-6">
                <div class="bg-white/20 p-4 rounded-full shadow-inner">
                    <i class="fa-solid fa-envelope text-2xl"></i>
                </div>
            </div>

            <!-- Judul -->
            <h1 class="text-2xl font-bold mb-2">Verifikasi Email Kamu </h1>
            <p class="text-sm text-gray-200 leading-relaxed">
                Terima kasih sudah mendaftar!
                Silakan verifikasi email kamu dengan mengklik tautan yang kami kirimkan.
                Jika belum menerima email, kamu bisa kirim ulang.
            </p>

            <!-- Pesan Sukses -->
            @if (session('status') == 'verification-link-sent')
                <div
                    class="mt-4 text-sm font-medium text-green-300 bg-green-800/30 py-2 px-4 rounded-xl border border-green-400/30">
                    Tautan verifikasi baru telah dikirim ke email kamu.
                </div>
            @endif

            <!-- Tombol -->
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-500 hover:bg-indigo-600 rounded-xl font-semibold shadow-md hover:shadow-indigo-400/50 transition-all duration-300">
                        Kirim Ulang Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-6 py-2 bg-white/20 hover:bg-white/30 rounded-xl font-semibold text-white shadow-md transition-all duration-300">
                        Keluar
                    </button>
                </form>
            </div>
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

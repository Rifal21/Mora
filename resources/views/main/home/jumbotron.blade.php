@auth
        <div class="container mx-auto px-4 py-10 mt-20">

            <div class="relative flex flex-col items-center justify-center overflow-hidden mb-5">
                <!-- Background bokeh particles -->
                <div id="bokeh-container" class="absolute inset-0"></div>

                <!-- Mora Finance -->
                <h1 id="moraTitle"
                    class="text-5xl sm:text-6xl font-extrabold text-center bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent relative mb-2">
                    Mora Finance
                    <span class="absolute inset-0 shimmer"></span>
                </h1>

                <!-- Greeting -->
                <div id="greeting"
                    class="flex flex-col sm:flex-row items-center justify-center gap-3 text-center bg-white/5 backdrop-blur-lg px-6 py-1">
                    <p id="welcomeText" class="text-lg sm:text-2xl font-semibold tracking-wide text-slate-600 drop-shadow-md">
                        Selamat Datang,
                        <span id="userName"
                            class="font-serif font-bold text-slate-700 bg-clip-text bg-gradient-to-r from-indigo-400 to-pink-400">
                            {{ Auth::user()->name }}
                        </span>
                    </p>
                    <img id="waveHand" src="{{ asset('assets/images/hi.gif') }}" class="w-36 sm:w-36 opacity-0" alt="👋">
                </div>
            </div>

            @push('styles')
                <style>
                    /* shimmer effect */
                    .shimmer::after {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: -100%;
                        width: 60%;
                        height: 100%;
                        background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.5), transparent);
                        animation: shimmerMove 3s infinite;
                    }

                    @keyframes shimmerMove {
                        0% {
                            left: -100%;
                        }

                        50% {
                            left: 100%;
                        }

                        100% {
                            left: 100%;
                        }
                    }

                    /* bokeh soft dots */
                    #bokeh-container {
                        position: absolute;
                        overflow: hidden;
                        z-index: 0;
                    }

                    .bokeh {
                        position: absolute;
                        border-radius: 50%;
                        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
                        animation: floatUp linear infinite;
                    }

                    @keyframes floatUp {
                        from {
                            transform: translateY(100vh) scale(0.5);
                            opacity: 0;
                        }

                        to {
                            transform: translateY(-10vh) scale(1);
                            opacity: 1;
                        }
                    }
                </style>
            @endpush

            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.2/lib/anime.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        // --- BOkeh effect ---
                        const container = document.getElementById('bokeh-container');
                        for (let i = 0; i < 20; i++) {
                            const b = document.createElement('div');
                            b.classList.add('bokeh');
                            b.style.width = `${Math.random() * 20 + 10}px`;
                            b.style.height = b.style.width;
                            b.style.left = `${Math.random() * 100}%`;
                            b.style.animationDuration = `${Math.random() * 8 + 6}s`;
                            b.style.animationDelay = `${Math.random() * 5}s`;
                            container.appendChild(b);
                        }

                        // --- Mora Finance Animation ---
                        anime({
                            targets: '#moraTitle',
                            opacity: [0, 1],
                            scale: [0.9, 1],
                            translateY: [20, 0],
                            easing: 'easeOutElastic(1, .9)',
                            duration: 1800,
                            delay: 300
                        });

                        // --- Greeting Text ---
                        anime({
                            targets: '#greeting',
                            opacity: [0, 1],
                            translateY: [30, 0],
                            easing: 'easeOutExpo',
                            duration: 1200,
                            delay: 1200
                        });

                        // --- User Name Typing Effect ---
                        const userName = document.querySelector('#userName');
                        const text = userName.textContent;
                        userName.textContent = '';
                        [...text].forEach((ch, i) => {
                            const span = document.createElement('span');
                            span.textContent = ch;
                            span.style.opacity = 0;
                            span.style.display = 'inline-block';
                            userName.appendChild(span);
                        });
                        anime({
                            targets: '#userName span',
                            opacity: [0, 1],
                            translateY: [10, 0],
                            delay: anime.stagger(70, {
                                start: 1600
                            }),
                            easing: 'easeOutBack'
                        });

                        // --- Wave hand after typing done ---
                        anime({
                            targets: '#waveHand',
                            opacity: [0, 1],
                            duration: 600,
                            delay: 2200,
                            easing: 'easeOutExpo',
                            complete: () => {
                                anime({
                                    targets: '#waveHand',
                                    rotate: [{
                                        value: -15,
                                        duration: 300
                                    }, {
                                        value: 15,
                                        duration: 300
                                    }],
                                    loop: true,
                                    direction: 'alternate',
                                    easing: 'easeInOutSine'
                                });
                            }
                        });
                    });
                </script>
            @endpush



            <!-- Summary Cards -->
            <div class="mb-8">
                <!-- Swiper Wrapper (hanya aktif di mobile) -->
                <div class="block md:hidden">
                    <div class="swiper mySummarySwiper">
                        <div class="swiper-wrapper">

                            <!-- Saldo -->
                            <div class="swiper-slide">
                                <div
                                    class="bg-white rounded-2xl p-6 border-b-8 border-r-8 border-l border-t-2  border-blue-600">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-lg font-semibold text-gray-700">Saldo Saat Ini</h2>
                                        <i class="fa-solid fa-wallet text-blue-600 text-xl"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-gray-900 mt-3">
                                        Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Pemasukan -->
                            <div class="swiper-slide">
                                <div
                                    class="bg-white rounded-2xl p-6 border-b-8 border-r-8 border-l border-t-2  border-green-500">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-lg font-semibold text-gray-700">Total Pemasukan</h2>
                                        <i class="fa-solid fa-circle-arrow-up text-green-500 text-xl"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-gray-900 mt-3">
                                        Rp {{ number_format($income ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Pengeluaran -->
                            <div class="swiper-slide">
                                <div class="bg-white rounded-2xl p-6 border-b-8 border-r-8 border-l border-t-2  border-red-500">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-lg font-semibold text-gray-700">Total Pengeluaran</h2>
                                        <i class="fa-solid fa-circle-arrow-down text-red-500 text-xl"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-gray-900 mt-3">
                                        Rp {{ number_format($expense ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="swiper-pagination mt-4"></div>
                    </div>
                </div>

                <!-- Grid layout (desktop) -->
                <div class="hidden md:grid grid-cols-3 gap-6">
                    <!-- Saldo -->
                    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-blue-600">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-700">Saldo Saat Ini</h2>
                            <i class="fa-solid fa-wallet text-blue-600 text-xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-3">
                            Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Pemasukan -->
                    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-700">Total Pemasukan</h2>
                            <i class="fa-solid fa-circle-arrow-up text-green-500 text-xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-3">
                            Rp {{ number_format($income ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Pengeluaran -->
                    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-700">Total Pengeluaran</h2>
                            <i class="fa-solid fa-circle-arrow-down text-red-500 text-xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-3">
                            Rp {{ number_format($expense ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>


            <!-- Content: Grafik + Transaksi Terakhir -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Grafik -->
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-indigo-600"></i> Ringkasan Transaksi
                    </h2>
                    <canvas id="transactionChart" height="180"></canvas>
                </div>

                <!-- Transaksi Terakhir -->
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <div class="flex justify-between items-start">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-indigo-600"></i> Transaksi Terakhir
                        </h2>
                        @if (auth()->check() && auth()->user()->profile->user_type === 'free')
                            <p>
                                sisa transaksi: <span class="text-red-500">{{ auth()->user()->profile->quota_trx }}x</span>
                            </p>
                        @endif
                    </div>

                    @if ($transactions->isEmpty())
                        <p class="text-gray-500 italic">Belum ada transaksi.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($transactions as $trx)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $trx->notes ?? $trx->invoice_number }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $trx->created_at ? \Carbon\Carbon::parse($trx->created_at)->format('d M Y') : '' }}
                                        </p>
                                    </div>
                                    <p class="font-semibold {{ $trx->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $trx->type === 'income' ? '+' : '-' }}
                                        Rp{{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endauth

    @guest
        <section class="relative h-screen flex items-center justify-center bg-cover bg-center"
            style="background-image: url('{{ asset('assets/images/bg.jpeg') }}');">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/80"></div>

            <!-- Content -->
            <div class="relative z-10 text-center text-white px-6 max-w-full">
                <!-- Logo / Title -->
                <h1
                    class="text-6xl md:text-7xl font-extrabold mb-6 bg-clip-text text-transparent 
                   bg-gradient-to-r from-indigo-400 via-sky-400 to-blue-600 drop-shadow-[0_5px_15px_rgba(99,102,241,0.4)] 
                   animate-gradient-x">
                    Mora Finance
                </h1>

                <!-- Subtitle -->
                <h3 class="text-3xl md:text-5xl font-bold mb-6 leading-tight">
                    Kelola Keuangan Bisnismu dengan Mudah, Cepat, dan Akurat
                </h3>

                <!-- Description -->
                <p class="text-lg text-gray-300 mb-10">
                    Catat pemasukan, pantau pengeluaran, dan nikmati laporan keuangan real-time dalam satu platform yang modern
                    dan aman.
                </p>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}"
                        class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700
                       text-white font-semibold px-8 py-3 rounded-full transition-all shadow-lg hover:shadow-indigo-500/30">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="bg-white/90 text-indigo-700 hover:bg-white font-semibold px-8 py-3 rounded-full 
                       transition-all shadow-md hover:shadow-lg">
                        Masuk
                    </a>
                </div>
            </div>

            <!-- Custom Gradient Animation -->
            <style>
                @keyframes gradient-x {

                    0%,
                    100% {
                        background-position: 0% 50%;
                    }

                    50% {
                        background-position: 100% 50%;
                    }
                }

                .animate-gradient-x {
                    background-size: 200% 200%;
                    animation: gradient-x 5s ease infinite;
                }
            </style>
        </section>
    @endguest
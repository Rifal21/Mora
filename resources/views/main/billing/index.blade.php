@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-24 lg:mt-28 px-6 max-w-6xl">

        @php
            $user = Auth::user();
            $profile = $user->profile;
        @endphp

        {{-- 🧑‍💼 Jika Super Admin --}}
        @if ($user->role && $user->role->name === 'Super Admin')
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">👑 Manajemen Langganan Pengguna</h1>

            <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
                <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Tipe Paket</th>
                            <th class="px-6 py-3 text-left">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left">Tanggal Berakhir</th>
                            <th class="px-6 py-3 text-left">Sisa Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($allUsers as $u)
                            @php
                                $p = $u->profile;
                                $end = $u->subscriptions->first()?->end_date
                                    ? \Carbon\Carbon::parse($u->subscriptions->first()->end_date)
                                    : null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $u->name }}</td>
                                <td class="px-6 py-4">{{ $u->email }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $p->user_type === 'pro' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                        {{ strtoupper($p->user_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $u->subscriptions->first()?->start_date ? \Carbon\Carbon::parse($u->subscriptions->first()?->start_date)->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $u->subscriptions->first()?->end_date ? \Carbon\Carbon::parse($u->subscriptions->first()?->end_date)->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($u->subscriptions->first()?->end_date)
                                        @php
                                            $remaining = \Carbon\Carbon::now()->diffInSeconds($end, false);
                                        @endphp
                                        @if ($remaining > 0)
                                            <span class="text-indigo-600 font-semibold countdown"
                                                data-end="{{ $end->format('Y-m-d H:i:s') }}"></span>
                                        @else
                                            <span class="text-red-600 font-semibold">Expired</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">Belum ada pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const countdowns = document.querySelectorAll(".countdown");
                    countdowns.forEach((el) => {
                        const end = new Date(el.dataset.end).getTime();

                        function update() {
                            const now = new Date().getTime();
                            const diff = end - now;
                            if (diff <= 0) {
                                el.innerText = "Expired";
                                return;
                            }

                            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                            el.innerText = `${days}h ${hours}j ${minutes}m ${seconds}d`;
                        }

                        update();
                        setInterval(update, 1000);
                    });
                });
            </script>

            {{-- 👤 Jika user biasa --}}
        @elseif ($profile->user_type === 'pro' && isset($subscription->start_date) && isset($subscription->end_date))
            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-6 mb-8 text-center shadow-sm">
                <h2 class="text-2xl font-bold text-indigo-700 mb-2">⭐ Langganan Aktif</h2>
                <p class="text-gray-600 mb-4">
                    Kamu sedang berlangganan paket <strong>PRO</strong>
                </p>

                <div class="flex justify-center items-center gap-8 text-sm text-gray-700 mb-4">
                    <div>
                        <p class="font-semibold">Mulai</p>
                        <p>{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Berakhir</p>
                        <p>{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Waktu Tersisa:</h3>
                    <div id="countdown" class="text-2xl font-bold text-indigo-600"></div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const endDate = new Date(
                        "{{ \Carbon\Carbon::parse($subscription->end_date)->format('Y-m-d H:i:s') }}").getTime();
                    const countdownEl = document.getElementById("countdown");

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = endDate - now;

                        if (distance <= 0) {
                            countdownEl.innerHTML = "Langganan sudah berakhir ❌";
                            return;
                        }

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        countdownEl.innerHTML = `${days}h ${hours}j ${minutes}m ${seconds}d`;
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                });
            </script>

            {{-- 🆓 Jika masih Free --}}
        @else
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">💳 Pilih Paket Langganan</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($plans as $plan)
                    <div
                        class="bg-white shadow-md rounded-2xl border border-gray-200 p-6 flex flex-col items-center hover:shadow-lg transition">
                        <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $plan->name }}</h2>
                        <p class="text-gray-500 text-center mb-4">{{ $plan->description }}</p>

                        <div class="text-4xl font-extrabold text-indigo-600 mb-2">
                            Rp {{ number_format($plan->price, 0, ',', '.') }}
                        </div>
                        <p class="text-sm text-gray-500 mb-4">/{{ $plan->duration_days }} hari</p>

                        @if ($plan->features && is_array($plan->features))
                            <ul class="text-gray-700 text-sm mb-6 space-y-1 w-full">
                                @foreach ($plan->features as $feature)
                                    <li class="flex items-center gap-2">
                                        <i class="fa-solid fa-check text-green-500"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if ($plan->price == 0)
                            <button disabled
                                class="bg-gray-300 text-gray-600 px-6 py-2 rounded-lg font-semibold w-full cursor-not-allowed">
                                Paket Gratis Aktif
                            </button>
                        @else
                            <a href="{{ route('billing.checkout', $plan->id) }}"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition w-full text-center">
                                Pilih Paket
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

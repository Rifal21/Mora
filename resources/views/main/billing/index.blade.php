@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-24 lg:mt-28 px-6 max-w-6xl">

        @php
            $user = Auth::user();
            $profile = $user->profile;
        @endphp

        {{-- 🧑‍💼 Jika Super Admin --}}
        @if ($user->role && $user->role->name === 'Super Admin')
            <h2 class="text-2xl font-bold text-gray-800 mt-12 mb-6 text-center">🧾 Riwayat Transaksi</h2>

            <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
                <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Invoice Number</th>
                            <th class="px-6 py-3 text-left">User</th>
                            <th class="px-6 py-3 text-left">Paket</th>
                            <th class="px-6 py-3 text-left">Jumlah</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($transactions as $trx)
                            <tr>
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">{{ $trx->invoice_number }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $trx->user->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $trx->plan->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    Rp{{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $trx->status === 'success' ? 'bg-green-100 text-green-700' : ($trx->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($trx->paid_at)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center mt-10">👑 Manajemen Langganan Pengguna</h1>

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

            <div class="mt-4">
                {{ $allUsers->links() }}
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
            <div
                class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-2xl shadow-lg p-8 mb-10">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl"></div>

                <h2 class="text-3xl font-bold mb-3 flex items-center justify-center gap-2">
                    <span>⭐ Langganan Aktif</span>
                </h2>

                <p class="text-indigo-100 text-lg mb-6 text-center">
                    Kamu sedang berlangganan paket<br><br>
                    <span
                        class="font-semibold text-white uppercase bg-green-600 px-3 py-2 rounded-full">{{ $subscription->plan->name ?? 'PRO' }}</span>
                </p>

                <div class="flex flex-col md:flex-row justify-center items-center gap-8 mb-6">
                    <div class="text-center">
                        <p class="text-sm text-indigo-200">Mulai</p>
                        <p class="text-lg font-semibold">
                            {{ \Carbon\Carbon::parse($subscription->start_date)->translatedFormat('d M Y H:i') }}
                        </p>
                    </div>
                    <div class="hidden md:block h-10 w-[1px] bg-white/30"></div>
                    <div class="text-center">
                        <p class="text-sm text-indigo-200">Berakhir</p>
                        <p class="text-lg font-semibold">
                            {{ \Carbon\Carbon::parse($subscription->end_date)->translatedFormat('d M Y H:i') }}
                        </p>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 max-w-md mx-auto shadow-inner">
                    <h3 class="text-lg font-semibold mb-2 text-center">⏳ Waktu Tersisa</h3>
                    <div id="countdown"
                        class="text-3xl font-extrabold text-center tracking-wide animate-pulse text-yellow-300"></div>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mt-12 mb-6 text-center">🧾 Riwayat Transaksi</h2>

            <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
                <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Invoice Number</th>
                            <th class="px-6 py-3 text-left">User</th>
                            <th class="px-6 py-3 text-left">Paket</th>
                            <th class="px-6 py-3 text-left">Jumlah</th>
                            <th class="px-6 py-3 text-left">Metode Pembayaran</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Dibayar</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($transactions as $trx)
                            <tr>
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">{{ $trx->invoice_number }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $trx->user->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $trx->plan->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    Rp{{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $trx->payment_method }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $trx->status === 'success' ? 'bg-green-100 text-green-700' : ($trx->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $trx->paid_at ? \Carbon\Carbon::parse($trx->paid_at)->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($trx->status === 'pending' && $trx->paid_at === null)
                                        <a href="{{ $trx->payment_url }}"
                                            class="px-3 py-2 bg-indigo-500 text-white rounded-lg" title="Bayar"><i
                                                class="fa-solid fa-coins text=lg"></i></a>
                                    @elseif ($trx->status === 'success')
                                        <a href="#" onclick="alert('Comming Soon')"
                                            class="px-3 py-2 bg-green-500 text-white rounded-lg" title="Receipt"><i
                                                class="fa-solid fa-receipt text=lg"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const endDate = new Date(
                        "{{ \Carbon\Carbon::parse($subscription->end_date)->format('Y-m-d H:i:s') }}"
                    ).getTime();
                    const countdownEl = document.getElementById("countdown");

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = endDate - now;

                        if (distance <= 0) {
                            countdownEl.innerHTML = "Langganan sudah berakhir ❌";
                            countdownEl.classList.remove("animate-pulse");
                            countdownEl.classList.add("text-red-400");
                            return;
                        }

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        countdownEl.innerHTML = `
                ${days} <span class="text-sm font-normal text-indigo-200">hari</span> 
                ${hours} <span class="text-sm font-normal text-indigo-200">jam</span> 
                ${minutes} <span class="text-sm font-normal text-indigo-200">mnt</span> 
                ${seconds} <span class="text-sm font-normal text-indigo-200">dtk</span>`;
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                });
            </script>
        @else
            <section class="container mx-auto px-4 py-20 mt-10">
                <div class="text-center mb-16">
                    <h2
                        class="font-extrabold text-4xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
                        Pilih Paket Langganan
                    </h2>
                    <p class="text-gray-500 text-lg">
                        Pilih paket sesuai kebutuhanmu dan nikmati semua fitur premium untuk bisnismu.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($plans as $package)
                        <div
                            class="relative bg-white/70 backdrop-blur-xl rounded-3xl shadow-lg p-8 border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">

                            @if ($loop->first)
                                <span
                                    class="absolute top-5 right-5 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    POPULER
                                </span>
                            @endif

                            <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                                {{ $package->name }}
                            </h3>
                            <p class="text-gray-500 mb-6 h-16">{{ Str::limit($package->description, 80) }}</p>

                            @php
                                $durationLabel =
                                    $package->duration_days < 30
                                        ? '/hari'
                                        : ($package->duration_days < 365
                                            ? '/bulan'
                                            : '/tahun');
                                $originalPrice = $package->price * 1.5;
                            @endphp

                            <div class="mb-6">
                                <p class="text-gray-400 text-lg line-through mb-1">
                                    Rp{{ number_format($originalPrice, 0, ',', '.') }}
                                </p>
                                <p class="text-4xl font-extrabold text-indigo-600">
                                    Rp{{ number_format($package->price, 0, ',', '.') }}
                                    <span class="text-gray-500 text-lg font-medium">{{ $durationLabel }}</span>
                                </p>
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 
                           text-white py-3 rounded-full font-semibold shadow-md hover:shadow-indigo-400/40 transition-all">
                                    Tambahkan ke Keranjang
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mt-12 mb-6 text-center">🧾 Riwayat Transaksi</h2>

                <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
                    <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Invoice Number</th>
                                <th class="px-6 py-3 text-left">User</th>
                                <th class="px-6 py-3 text-left">Paket</th>
                                <th class="px-6 py-3 text-left">Jumlah</th>
                                <th class="px-6 py-3 text-left">Metode Pembayaran</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-left">Dibayar</th>
                                <th class="px-6 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-700">
                            @forelse ($transactions as $trx)
                                <tr>
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">{{ $trx->invoice_number }}</td>
                                    <td class="px-6 py-4 font-semibold">{{ $trx->user->name ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $trx->plan->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        Rp{{ number_format($trx->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $trx->payment_method ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $trx->status === 'success' ? 'bg-green-100 text-green-700' : ($trx->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ ucfirst($trx->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $trx->paid_at ? \Carbon\Carbon::parse($trx->paid_at)->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($trx->status === 'pending' && $trx->paid_at === null)
                                            <a href="{{ $trx->payment_url }}"
                                                class="px-3 py-2 bg-indigo-500 text-white rounded-lg" title="Bayar"><i
                                                    class="fa-solid fa-coins text=lg"></i></a>
                                        @elseif ($trx->status === 'success')
                                            <a href="#" onclick="alert('Comming Soon')"
                                                class="px-3 py-2 bg-green-500 text-white rounded-lg" title="Receipt"><i
                                                    class="fa-solid fa-receipt text=lg"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>

            </section>
        @endif
    </div>
@endsection

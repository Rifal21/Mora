@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10 mt-20">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h1>
            <a href="{{ route('transactions.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition-all w-full sm:w-auto text-center">
                <i class="fa-solid fa-plus mr-1"></i> Tambah Transaksi
            </a>
        </div>

        <!-- Table Wrapper -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700 hidden md:table">
                    <thead class="bg-gray-100 border-b text-gray-800 uppercase text-xs font-semibold">
                        <tr>
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Invoice</th>
                            <th class="py-3 px-4 text-left">Customer</th>
                            <th class="py-3 px-4 text-left">Total</th>
                            <th class="py-3 px-4 text-left">Payment</th>
                            <th class="py-3 px-4 text-left">Tipe</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Catatan</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $trx)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $trx->invoice_number }}</td>
                                <td class="py-3 px-4">{{ $trx->customer_name ?? auth()->user()->name }}</td>
                                <td class="py-3 px-4">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 capitalize">{{ $trx->payment_method ?? 'Pribadi' }}</td>
                                <td class="py-3 px-4 capitalize">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $trx->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {!! $trx->type === 'income' ? '<i class="fa-solid fa-arrow-up"></i>' : '<i class="fa-solid fa-arrow-down"></i>' !!}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $trx->status === 'success' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-600">{{ $trx->notes }}</td>
                                <td class="py-3 px-4 text-center space-x-2">
                                    @if ($trx->status === 'pending' && $trx->payment_method === 'qris')
                                        <form action="{{ route('transactions.confirm', $trx->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs">
                                                Check
                                            </button>
                                        </form>
                                    @elseif($trx->status === 'success')
                                        <a href="{{ route('transactions.print', $trx->id) }}" target="_blank"
                                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-xs">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-5 text-center text-gray-500 italic">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (Card Style) -->
            <div class="block md:hidden divide-y">
                @forelse ($transactions as $trx)
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700">{{ $trx->invoice_number }}</span>
                            <span
                                class="text-xs px-2 py-1 rounded-full font-semibold
                                {{ $trx->status === 'success' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </div>
                        <div class="text-gray-600 text-sm mb-1">
                            💰 Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                        </div>
                        <div class="text-gray-500 text-xs mb-2">
                            {{ $trx->customer_name ?? auth()->user()->name }} •
                            {{ ucfirst($trx->payment_method ?? 'Pribadi') }}
                        </div>

                        <div class="flex justify-between items-center">
                            <div>
                                {!! $trx->type === 'income'
                                    ? '<span class="text-green-600 text-xs"><i class="fa-solid fa-arrow-up mr-1"></i>Income</span>'
                                    : '<span class="text-red-600 text-xs"><i class="fa-solid fa-arrow-down mr-1"></i>Expense</span>' !!}
                            </div>

                            <div class="flex gap-2">
                                @if ($trx->status === 'pending' && $trx->payment_method === 'qris')
                                    <form action="{{ route('transactions.confirm', $trx->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-xs">
                                            Check
                                        </button>
                                    </form>
                                @elseif($trx->status === 'success')
                                    <a href="{{ route('transactions.print', $trx->id) }}" target="_blank"
                                        class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-xs">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500 italic">
                        Belum ada transaksi.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="flex justify-end items-center mt-4">
            {{ $transactions->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Modal Tambah Transaksi -->
    <div id="addTransactionModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative">
            <button id="closeModalBtn" class="absolute top-3 right-4 text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <h2 class="text-xl font-semibold mb-4 text-gray-800">Tambah Transaksi Untuk catatan keuangan</h2>

            <form action="{{ route('transactions.store') }}" method="POST" class="flex flex-col gap-3">
                @csrf

                {{-- <input type="hidden" name="bisnis_id" value="{{ session('bisnis_id') }}"> --}}
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="trx_type" value="catkeu">

                <select name="type" class="border border-gray-300 rounded p-2 w-full focus:ring-2 focus:ring-blue-400"
                    required>
                    <option value="">Pilih Tipe</option>
                    <option value="income">Pemasukan</option>
                    <option value="expense">Pengeluaran</option>
                </select>

                <input type="number" name="total_amount" placeholder="Total Transaksi"
                    class="border border-gray-300 rounded p-2 w-full focus:ring-2 focus:ring-blue-400" required>

                <textarea name="notes" placeholder="Catatan" rows="3"
                    class="border border-gray-300 rounded p-2 w-full focus:ring-2 focus:ring-blue-400"></textarea>

                <button type="submit" class="bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-all">
                    Simpan Transaksi
                </button>
            </form>
        </div>
    </div>

    <script>
        // Modal logic
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const modal = document.getElementById('addTransactionModal');

        openModalBtn.addEventListener('click', () => modal.classList.remove('hidden'));
        closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.add('hidden');
        });
    </script>
@endsection

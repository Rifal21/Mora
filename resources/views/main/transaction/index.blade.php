@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto p-4 mt-24">
        <!-- Pilih Bisnis -->
        <div class="flex justify-center mb-8">
            <form action="{{ route('set') }}" method="POST" class="flex gap-2 w-full max-w-md">
                @csrf
                <select name="bisnis_id" onchange="this.form.submit()"
                    class="border border-gray-300 p-3 rounded-lg flex-1 shadow-sm bg-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
                    <option value="">-- Pilih Bisnis --</option>
                    @foreach ($bisnis->where('user_id', Auth::id())->where('status', 'active') as $b)
                        <option value="{{ $b->id }}" {{ session('bisnis_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Produk List -->
            <div
                class="lg:w-1/2 w-full bg-white/70 backdrop-blur-sm p-5 rounded-2xl shadow-[0_4px_16px_rgba(0,0,0,0.08)] border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-bold text-xl text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-indigo-500"></i> Semua Produk
                    </h2>
                    <input type="text" id="search-product" placeholder="Cari produk..."
                        class="border p-2 rounded-lg text-sm shadow-sm focus:ring-2 focus:ring-indigo-400 w-1/2 transition">
                </div>

                <div id="product-list"
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 overflow-y-auto max-h-[65vh] pr-1">
                    @foreach ($products as $product)
                        @php
                            $image = null;
                            if (isset($product->images)) {
                                $imgArray = is_array($product->images)
                                    ? $product->images
                                    : json_decode($product->images, true);
                                $image = $imgArray[0] ?? null;
                            } elseif (isset($product->image)) {
                                $image = $product->image;
                            }
                        @endphp

                        <button
                            class="product-btn group bg-gradient-to-br from-white to-gray-50 border border-gray-200 hover:border-indigo-400 hover:shadow-lg transition-all duration-200 rounded-xl p-3 flex flex-col items-center justify-between text-gray-800 relative overflow-hidden"
                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                            data-price="{{ $product->price }}">
                            @if ($image)
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                    class="w-24 h-24 object-cover rounded-lg mb-2 transition-transform duration-200 group-hover:scale-105">
                            @else
                                <div
                                    class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 mb-2">
                                    <i class="fa-solid fa-image text-2xl"></i>
                                </div>
                            @endif
                            <div class="text-center">
                                <div class="font-semibold text-sm truncate">{{ $product->name }}</div>
                                <div class="text-xs">stock: {{ $product->stock }}</div>
                                <div class="text-sm text-indigo-600 mt-1 font-medium">
                                    Rp {{ number_format($product->price) }}
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Transaction Cart -->
            <div
                class="lg:w-1/2 w-full bg-white/70 backdrop-blur-sm p-5 rounded-2xl shadow-[0_4px_16px_rgba(0,0,0,0.08)] border border-gray-100 flex flex-col h-[70vh]">
                <h2 class="font-bold text-xl mb-4 text-center text-gray-800 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-shopping text-green-500"></i> Keranjang
                </h2>

                <div id="cart-items" class="flex-1 overflow-y-auto mb-4 space-y-2"></div>

                <div class="border-t pt-3 mt-auto">
                    <div class="flex justify-between font-semibold text-lg mb-3">
                        <span>Total:</span>
                        <span class="text-indigo-600">Rp <span id="cart-total">0</span></span>
                    </div>

                    <form method="POST" action="{{ route('transactions.store') }}" id="transaction-form"
                        class="flex flex-col gap-3">
                        @csrf
                        <input type="hidden" name="bisnis_id" value="{{ session('bisnis_id') }}">
                        <input type="hidden" name="items" id="items-input">

                        <input type="text" name="customer_name" placeholder="Nama Customer"
                            class="border p-2 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-400">

                        <select name="payment_method" id="payment-method"
                            class="border p-2 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-400" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                        </select>

                        {{-- {{ dd(session('bisnis_qris')) }} --}}
                        <div id="qris-container" class="hidden bg-gray-50 border rounded-lg p-3 text-center mt-1 shadow-sm">
                            <img src="{{ session('bisnis_qris') ? asset('storage/' . session('bisnis_qris')) : '' }}"
                                alt="QRIS" class="w-40 mx-auto rounded-md">
                        </div>

                        <textarea name="notes" placeholder="Catatan (opsional)"
                            class="border p-2 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-400"></textarea>

                        <button type="submit"
                            class="bg-gradient-to-r from-green-500 to-green-600 text-white p-2 rounded-lg hover:from-green-600 hover:to-green-700 shadow-md transition-all font-medium">
                            💳 Bayar & Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div
            class="bg-white/70 backdrop-blur-sm p-5 rounded-2xl shadow-[0_4px_16px_rgba(0,0,0,0.08)] border border-gray-100 mt-8">
            <h2 class="font-bold text-xl mb-4 text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-indigo-500"></i> Riwayat Transaksi
            </h2>
            <div class="overflow-x-auto rounded-lg border">
                <table class="min-w-full text-sm">
                    <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                        <tr>
                            <th class="py-2 px-3 text-left">Invoice</th>
                            <th class="py-2 px-3 text-left">Customer</th>
                            <th class="py-2 px-3 text-left">Total</th>
                            <th class="py-2 px-3 text-left">Pembayaran</th>
                            <th class="py-2 px-3 text-left">Status</th>
                            <th class="py-2 px-3 text-left">Tanggal</th>
                            <th class="py-2 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($transactions as $tr)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2 px-3">{{ $tr->invoice_number }}</td>
                                <td class="py-2 px-3">{{ $tr->customer_name ?? '-' }}</td>
                                <td class="py-2 px-3 text-indigo-600 font-semibold">Rp
                                    {{ number_format($tr->total_amount) }}</td>
                                <td class="py-2 px-3 capitalize">{{ $tr->payment_method }}</td>
                                <td class="py-2 px-3">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $tr->status == 'success' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($tr->status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-3">{{ $tr->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-2 px-3 text-center">
                                    @if ($tr->status === 'pending' && $tr->payment_method === 'qris')
                                        <form action="{{ route('transactions.confirm', $tr->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @elseif($tr->status === 'success')
                                        <a href="{{ route('transactions.print', $tr->id) }}" target="_blank"
                                            class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let items = [];

        function renderCart() {
            const cartContainer = document.getElementById('cart-items');
            cartContainer.innerHTML = '';
            let total = 0;

            items.forEach((item, index) => {
                total += item.total;

                cartContainer.innerHTML += `
                    <div class="flex justify-between items-center bg-gray-50 border rounded-lg px-3 py-2 shadow-sm">
                        <div>
                            <div class="font-medium text-gray-800">${item.name}</div>
                            <div class="text-xs text-gray-500">Qty: ${item.quantity}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="text-indigo-600 font-semibold">Rp ${item.total.toLocaleString()}</div>
                            <button class="text-red-500 hover:text-red-700" onclick="removeItem(${index})">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>`;
            });

            document.getElementById('cart-total').innerText = total.toLocaleString();
            document.getElementById('items-input').value = JSON.stringify(items);
        }

        function addItem(id, name, price) {
            let found = items.find(i => i.product_id == id);
            if (found) {
                found.quantity++;
                found.total = found.quantity * found.price;
            } else {
                items.push({
                    product_id: id,
                    name,
                    quantity: 1,
                    price,
                    total: price
                });
            }
            renderCart();
        }

        function removeItem(index) {
            items.splice(index, 1);
            renderCart();
        }

        document.querySelectorAll('.product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                addItem(this.dataset.id, this.dataset.name, parseFloat(this.dataset.price));
            });
        });

        document.getElementById('payment-method').addEventListener('change', function() {
            document.getElementById('qris-container').classList.toggle('hidden', this.value !== 'qris');
        });

        document.getElementById('search-product').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            document.querySelectorAll('#product-list .product-btn').forEach(btn => {
                const name = btn.dataset.name.toLowerCase();
                btn.style.display = name.includes(search) ? 'flex' : 'none';
            });
        });
    </script>
@endsection

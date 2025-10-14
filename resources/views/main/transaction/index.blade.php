@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto p-4 mt-24">
        <!-- Pilih Bisnis -->
        <div class="mb-6 flex justify-center">
            <form action="{{ route('set') }}" method="POST" class="flex gap-2 w-full max-w-md">
                @csrf
                <select name="bisnis_id" onchange="this.form.submit()"
                    class="border border-gray-300 p-2 rounded flex-1 shadow-sm focus:ring-2 focus:ring-blue-400">
                    <option value="">-- Pilih Bisnis --</option>
                    @foreach ($bisnis as $b)
                        <option value="{{ $b->id }}" {{ session('bisnis_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="flex flex-col lg:flex-row items-start gap-4 mb-6">
            <!-- Produk List -->
            <div class="lg:w-1/2 w-full bg-white p-4 rounded-lg shadow-lg overflow-y-auto h-[70vh]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-bold text-xl">Semua Produk</h2>
                    <input type="text" id="search-product" placeholder="Cari produk..."
                        class="border p-2 rounded text-sm shadow-sm focus:ring-2 focus:ring-blue-400 w-1/2">
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="product-list">
                    @foreach ($products as $product)
                        @php
                            $image = null;
                            if (isset($product->images)) {
                                // Jika images adalah array JSON
                                $imgArray = is_array($product->images)
                                    ? $product->images
                                    : json_decode($product->images, true);
                                $image = $imgArray[0] ?? null;
                            } elseif (isset($product->image)) {
                                // Jika hanya satu kolom image
                                $image = $product->image;
                            }
                        @endphp

                        <button
                            class="product-btn bg-gray-50 border hover:border-blue-400 text-gray-800 p-3 rounded-lg shadow-sm hover:shadow-md transition-all flex flex-col items-center"
                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                            data-price="{{ $product->price }}">
                            @if ($image)
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                    class="w-20 h-20 object-cover rounded-md mb-2">
                            @else
                                <div
                                    class="w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center text-gray-500">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                            <div class="font-semibold text-sm text-center">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">Rp {{ number_format($product->price) }}</div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Transaction Cart -->
            <div class="lg:w-1/2 w-full bg-white p-4 rounded-lg shadow-lg flex flex-col h-[70vh]">
                <h2 class="font-bold text-xl mb-4 text-center">Cart</h2>
                <div id="cart-items" class="flex-1 overflow-y-auto mb-4"></div>

                <div class="mt-auto">
                    <div class="flex justify-between font-bold text-lg mb-3 border-b pb-2">
                        <span>Total:</span>
                        <span>Rp <span id="cart-total">0</span></span>
                    </div>

                    <form method="POST" action="{{ route('transactions.store') }}" id="transaction-form"
                        class="flex flex-col gap-3">
                        @csrf
                        <input type="hidden" name="bisnis_id" value="{{ session('bisnis_id') }}">
                        <input type="hidden" name="items" id="items-input">

                        <input type="text" name="customer_name" placeholder="Customer Name"
                            class="border p-2 rounded shadow-sm focus:ring-2 focus:ring-blue-400">

                        <select name="payment_method" id="payment-method"
                            class="border p-2 rounded shadow-sm focus:ring-2 focus:ring-blue-400" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                        </select>

                        <div id="qris-container" class="hidden text-center">
                            <img src="{{ session('bisnis_qris') ? asset('storage/' . session('bisnis_qris')) : '' }}"
                                alt="QRIS" class="w-48 mx-auto mt-2 rounded shadow-md">
                        </div>

                        <textarea name="notes" placeholder="Notes" class="border p-2 rounded shadow-sm focus:ring-2 focus:ring-blue-400"></textarea>

                        <button type="submit"
                            class="bg-green-500 text-white p-2 rounded hover:bg-green-600 shadow-md transition-all">
                            Pay & Save
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel List Transactions -->
        <div class="bg-white p-4 rounded-lg shadow-lg mt-6">
            <h2 class="font-bold text-xl mb-4 text-center">Transactions List</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-3 border-b">Invoice</th>
                            <th class="py-2 px-3 border-b">Customer</th>
                            <th class="py-2 px-3 border-b">Total</th>
                            <th class="py-2 px-3 border-b">Payment</th>
                            <th class="py-2 px-3 border-b">Status</th>
                            <th class="py-2 px-3 border-b">Date</th>
                            <th class="py-2 px-3 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $tr)
                            <tr class="text-center">
                                <td class="py-2 px-3 border-b">{{ $tr->invoice_number }}</td>
                                <td class="py-2 px-3 border-b">{{ $tr->customer_name ?? '-' }}</td>
                                <td class="py-2 px-3 border-b">Rp {{ number_format($tr->total_amount) }}</td>
                                <td class="py-2 px-3 border-b capitalize">{{ $tr->payment_method }}</td>
                                <td class="py-2 px-3 border-b capitalize">
                                    <span
                                        class="{{ $tr->status == 'success' ? 'text-green-600 font-semibold' : 'text-yellow-600 font-semibold' }}">
                                        {{ $tr->status }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 border-b">{{ $tr->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-2 px-3 border-b">
                                    @if ($tr->status === 'pending' && $tr->payment_method === 'qris')
                                        <form action="{{ route('transactions.confirm', $tr->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @elseif($tr->status === 'success')
                                        <a href="{{ route('transactions.print', $tr->id) }}" target="_blank"
                                            class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">No transactions yet.</td>
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

                const div = document.createElement('div');
                div.className = "flex justify-between items-center border-b py-2";

                div.innerHTML = `
                    <div>
                        <div class="font-semibold">${item.name}</div>
                        <div class="text-sm text-gray-500">Qty: ${item.quantity}</div>
                    </div>
                    <div class="flex gap-2 items-center">
                        <div>Rp ${item.total.toLocaleString()}</div>
                        <button class="text-red-500 hover:text-red-700" onclick="removeItem(${index})">✕</button>
                    </div>
                `;

                cartContainer.appendChild(div);
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
                    name: name,
                    quantity: 1,
                    price: price,
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
            const qrisContainer = document.getElementById('qris-container');
            qrisContainer.classList.toggle('hidden', this.value !== 'qris');
        });

        // 🔍 Search produk real-time
        document.getElementById('search-product').addEventListener('keyup', function() {
            const search = this.value.toLowerCase();
            document.querySelectorAll('#product-list .product-btn').forEach(btn => {
                const name = btn.dataset.name.toLowerCase();
                btn.style.display = name.includes(search) ? 'flex' : 'none';
            });
        });
    </script>
@endsection

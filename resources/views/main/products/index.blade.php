@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10 mt-24">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Semua Produk</h1>
            <button onclick="openModal('addProductModal')"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Produk
            </button>
        </div>

        <div class="hidden md:block overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Gambar</th>
                        <th class="px-6 py-3 text-left">Nama Produk</th>
                        <th class="px-6 py-3 text-left">Bisnis</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Harga</th>
                        <th class="px-6 py-3 text-left">Stock</th>
                        <th class="px-6 py-3 text-left">Unit</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $prod)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                @if ($prod->images && count($prod->images) > 0)
                                    <img src="{{ asset('storage/' . $prod->images[0]) }}"
                                        class="h-12 w-12 rounded object-cover mx-auto" alt="Gambar Produk">
                                @else
                                    <div
                                        class="h-12 w-12 bg-gray-200 rounded-full mx-auto flex items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $prod->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $prod->bisnis->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $prod->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $prod->stock }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $prod->unit }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold 
                            {{ $prod->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($prod->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex justify-center items-center gap-3">
                                <button class="edit-product-btn" data-id="{{ $prod->id }}"
                                    data-bisnis_id="{{ $prod->bisnis_id }}" data-category_id="{{ $prod->category_id }}"
                                    data-name="{{ $prod->name }}" data-price="{{ $prod->price }}"
                                    data-stock="{{ $prod->stock }}" data-unit="{{ $prod->unit }}"
                                    data-description="{{ $prod->description }}" data-status="{{ $prod->status }}"
                                    data-images='@json($prod->images ?? [])'>
                                    <i class="fa-solid fa-pen text-blue-500"></i>
                                </button>

                                <form action="{{ route('products.destroy', $prod->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500 italic">
                                Tidak ada produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @forelse ($products as $prod)
                <div class="bg-white rounded-xl shadow p-4 flex flex-col gap-3">
                    <div class="flex gap-4">
                        @if ($prod->images && count($prod->images) > 0)
                            <img src="{{ asset('storage/' . $prod->images[0]) }}" class="h-16 w-16 rounded object-cover"
                                alt="Gambar Produk">
                        @else
                            <div
                                class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center text-gray-500 text-lg">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif

                        <div class="flex-1">
                            <h2 class="font-semibold text-gray-800">{{ $prod->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $prod->category->name ?? '-' }}</p>
                            <p class="text-sm text-gray-600 mt-1">Rp {{ number_format($prod->price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-2">
                        <span
                            class="px-2 py-1 rounded-full text-xs font-semibold 
                            {{ $prod->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($prod->status) }}
                        </span>

                        <div class="flex gap-3">
                            <button class="edit-product-btn" data-id="{{ $prod->id }}"
                                data-bisnis_id="{{ $prod->bisnis_id }}" data-category_id="{{ $prod->category_id }}"
                                data-name="{{ $prod->name }}" data-price="{{ $prod->price }}"
                                data-stock="{{ $prod->stock }}" data-unit="{{ $prod->unit }}"
                                data-description="{{ $prod->description }}" data-status="{{ $prod->status }}"
                                data-images='@json($prod->images ?? [])'>
                                <i class="fa-solid fa-pen text-blue-500"></i>
                            </button>

                            <form action="{{ route('products.destroy', $prod->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">
                                    <i class="fa-solid fa-trash text-red-500"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 italic">Tidak ada produk.</p>
            @endforelse
        </div>
    </div>

    @include('main.products.modals')

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('opacity-0', 'pointer-events-none');
        }
        document.querySelectorAll('.edit-product-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const product = {
                    id: btn.dataset.id,
                    bisnis_id: btn.dataset.bisnis_id,
                    category_id: btn.dataset.category_id,
                    name: btn.dataset.name,
                    price: btn.dataset.price,
                    stock: btn.dataset.stock,
                    unit: btn.dataset.unit,
                    description: btn.dataset.description,
                    status: btn.dataset.status,
                    images: JSON.parse(btn.dataset.images || '[]')
                };
                openEditModal(product);
            });
        });

        function openEditModal(product) {
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_bisnis_id').value = product.bisnis_id;
            document.getElementById('edit_category_id').value = product.category_id;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_stock').value = product.stock;
            document.getElementById('edit_unit').value = product.unit;
            document.getElementById('edit_description').value = product.description;
            document.getElementById('edit_status').value = product.status;

            const previewContainer = document.getElementById('editImagesPreview');
            previewContainer.innerHTML = '';

            if (Array.isArray(product.images) && product.images.length > 0) {
                product.images.forEach(imgPath => {
                    // Pastikan path tidak null dan trim spasi
                    if (imgPath) {
                        const imageEl = document.createElement('img');
                        // Jika path sudah mengandung 'storage/', jangan tambah lagi
                        imageEl.src = imgPath.startsWith('/storage/') ? imgPath : '/storage/' + imgPath;
                        imageEl.classList.add('h-20', 'object-contain', 'rounded', 'mr-2');
                        previewContainer.appendChild(imageEl);
                    }
                });
            }

            document.getElementById('editProductForm').action = `/products/${product.id}`;
            openModal('editProductModal');
        }


        function previewMultipleImages(event, previewId) {
            const input = event.target;
            const previewContainer = document.getElementById(previewId);
            previewContainer.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.classList.add('h-20', 'object-contain', 'rounded', 'mr-2');
                    previewContainer.appendChild(img);
                });
            }
        }
    </script>
@endsection

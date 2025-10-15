<!-- Modal Tambah Produk -->
<div id="addProductModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
    <div class="bg-white rounded-lg w-full max-w-2xl p-6 relative">
        <h3 class="text-lg font-bold mb-4">Tambah Produk</h3>
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Bisnis</label>
                    <select name="bisnis_id" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Bisnis</option>
                        @foreach ($bisnisList as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category_id" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories->where('status', 'active') as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" name="name" required
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex gap-3">
                <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="text" name="price" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="number" name="stock" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                    <input type="text" name="unit" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                <input type="file" name="images[]" multiple accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-600"
                    onchange="previewMultipleImages(event,'addImagesPreview')">
                <div id="addImagesPreview" class="flex gap-2 mt-2 flex-wrap"></div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('addProductModal')"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Batal</button>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Produk -->
<div id="editProductModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
    <div class="bg-white rounded-lg w-full max-w-2xl p-6 relative">
        <h3 class="text-lg font-bold mb-4">Edit Produk</h3>
        <form method="POST" id="editProductForm" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Bisnis</label>
                    <select name="bisnis_id" id="edit_bisnis_id" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($bisnisList as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category_id" id="edit_category_id" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($categories->where('status', 'active') as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" id="edit_name" name="name" required
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="edit_description" name="description" rows="3"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex gap-3">
                <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="text" id="edit_price" name="price" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Stock</label>
                    <input type="text" id="edit_stock" name="stock" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                    <input type="text" id="edit_unit" name="unit" required
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                <input type="file" name="images[]" multiple accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-600"
                    onchange="previewMultipleImages(event,'editImagesPreview')">
                <div id="editImagesPreview" class="flex gap-2 mt-2 flex-wrap"></div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select id="edit_status" name="status"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('editProductModal')"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Batal</button>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

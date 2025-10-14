<!-- Modal Tambah Kategori -->
<div id="addCategoryModal"
    class="fixed inset-0 flex items-center justify-center bg-black/50 opacity-0 pointer-events-none transition-opacity">
    <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
        <h3 class="font-bold text-lg mb-4">Tambah Kategori</h3>
        <form method="POST" action="{{ route('product-categories.store') }}" enctype="multipart/form-data"
            class="space-y-4">
            @csrf

            <div>
                <label for="bisnis_id" class="block text-sm font-medium text-gray-700">Bisnis</label>
                <select id="bisnis_id" name="bisnis_id"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach ($bisnisList as $bisnis)
                        <option value="{{ $bisnis->id }}">{{ $bisnis->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" id="name" name="name"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Gambar Kategori</label>
                <input type="file" id="image" name="image" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-600" onchange="previewImage(event, 'imagePreview')">
                <img id="imagePreview" src="" class="mt-2 h-20 object-contain hidden" alt="Preview Gambar">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('addCategoryModal')"
                    class="px-4 py-2 rounded-lg border">Batal</button>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="editCategoryModal"
    class="fixed inset-0 flex items-center justify-center bg-black/50 opacity-0 pointer-events-none transition-opacity">
    <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
        <h3 class="font-bold text-lg mb-4">Edit Kategori</h3>
        <form id="editCategoryForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_bisnis_id" class="block text-sm font-medium text-gray-700">Bisnis</label>
                <select id="edit_bisnis_id" name="bisnis_id"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach ($bisnisList as $bisnis)
                        <option value="{{ $bisnis->id }}">{{ $bisnis->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" id="edit_name" name="name"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
            </div>

            <div>
                <label for="edit_image" class="block text-sm font-medium text-gray-700">Gambar Kategori</label>
                <input type="file" id="edit_image" name="image" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-600" onchange="previewImage(event, 'editImagePreview')">
                <img id="editImagePreview" src="" class="mt-2 h-20 object-contain hidden" alt="Preview Gambar">
            </div>

            <div>
                <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="edit_status" name="status"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('editCategoryModal')"
                    class="px-4 py-2 rounded-lg border">Batal</button>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

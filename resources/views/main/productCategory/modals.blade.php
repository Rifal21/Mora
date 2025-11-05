<!-- ✅ Modal Tambah Kategori -->
<div id="addCategoryModal"
    class="fixed inset-0 flex items-center justify-center bg-black/50 opacity-0 pointer-events-none transition-opacity z-50">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 relative shadow-xl">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Tambah Kategori</h3>

        <form method="POST" action="{{ route('product-categories.store') }}" enctype="multipart/form-data"
            class="space-y-4">
            @csrf

            {{-- Bisnis --}}
            <div>
                <label for="bisnis_id" class="block text-sm font-medium text-gray-700">Bisnis</label>
                <select id="bisnis_id" name="bisnis_id"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('bisnis_id') border-red-500 @enderror">
                    <option value="" selected disabled>-- Pilih Bisnis --</option>
                    @foreach ($bisnisList as $bisnis)
                        <option value="{{ $bisnis->id }}" {{ old('bisnis_id') == $bisnis->id ? 'selected' : '' }}>
                            {{ $bisnis->name }}
                        </option>
                    @endforeach
                </select>
                @error('bisnis_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Kategori --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload Gambar --}}
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Kategori</label>

                <div
                    class="relative border-2 border-dashed rounded-xl p-4 text-center cursor-pointer hover:border-indigo-400 transition">
                    <input type="file" id="image" name="image" accept="image/*"
                        onchange="previewImage(event, 'imagePreview')"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                    <div id="uploadIcon" class="text-gray-500 flex flex-col items-center justify-center space-y-1">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl"></i>
                        <p class="text-sm">Klik untuk unggah gambar</p>
                        <p class="text-xs text-gray-400">Maksimal 2MB</p>
                    </div>
                    <img id="imagePreview" src="" class="hidden mx-auto h-24 rounded-lg object-cover"
                        alt="Preview Gambar">
                </div>

                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="edit_status" name="status"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('addCategoryModal')"
                    class="px-4 py-2 rounded-lg border hover:bg-gray-100 transition">Batal</button>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ Modal Edit Kategori -->
<div id="editCategoryModal"
    class="fixed inset-0 flex items-center justify-center bg-black/50 opacity-0 pointer-events-none transition-opacity z-50">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 relative shadow-xl">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Edit Kategori</h3>

        <form id="editCategoryForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Bisnis --}}
            <div>
                <label for="edit_bisnis_id" class="block text-sm font-medium text-gray-700">Bisnis</label>
                <select id="edit_bisnis_id" name="bisnis_id"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach ($bisnisList as $bisnis)
                        <option value="{{ $bisnis->id }}">{{ $bisnis->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Nama --}}
            <div>
                <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" id="edit_name" name="name"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
            </div>

            {{-- Upload Gambar --}}
            <div>
                <label for="edit_image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Kategori</label>

                <div
                    class="relative border-2 border-dashed rounded-xl p-4 text-center cursor-pointer hover:border-indigo-400 transition">
                    <input type="file" id="edit_image" name="image" accept="image/*"
                        onchange="previewImage(event, 'editImagePreview')"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                    <div id="editUploadIcon" class="text-gray-500 flex flex-col items-center justify-center space-y-1">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl"></i>
                        <p class="text-sm">Klik untuk ubah gambar</p>
                        <p class="text-xs text-gray-400">Maksimal 2MB</p>
                    </div>
                    <img id="editImagePreview" src="" class="hidden mx-auto h-24 rounded-lg object-cover"
                        alt="Preview Gambar">
                </div>
            </div>

            {{-- Toggle Status --}}
            <div>
                <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="edit_status" name="status"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('editCategoryModal')"
                    class="px-4 py-2 rounded-lg border hover:bg-gray-100 transition">Batal</button>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

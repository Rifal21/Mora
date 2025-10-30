<!-- Modal Edit Bisnis -->
<div id="editBisnisModal"
    class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div
        class="bg-white backdrop-blur-xl rounded-2xl shadow-xl w-full max-w-md max-h-[70vh] md:max-h-[75vh] p-6 relative border border-gray-200 overflow-auto">
        <h3 class="font-semibold text-xl text-gray-800 mb-5 flex items-center gap-2">
            <i class="fa-solid fa-pen text-indigo-600"></i> Edit Bisnis
        </h3>

        <form id="editBisnisForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nama Bisnis -->
            <div>
                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Bisnis</label>
                <input type="text" id="edit_name" name="name"
                    class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    required>
            </div>

            <!-- Alamat -->
            <div>
                <label for="edit_alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <input type="text" id="edit_alamat" name="alamat"
                    class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
            </div>

            <!-- Kontak -->
            <div class="flex gap-3">
                <div class="w-1/2">
                    <label for="edit_telepon" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" id="edit_telepon" name="telepon"
                        class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>
                <div class="w-1/2">
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="edit_email" name="email"
                        class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>
            </div>

            <!-- Upload Logo -->
            <div>
                <label for="edit_logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                <div
                    class="border border-dashed border-gray-300 rounded-xl p-3 text-center hover:border-indigo-400 transition">
                    <input type="file" id="edit_logo" name="logo" accept="image/*"
                        class="block w-full text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                        onchange="previewImage(event, 'editLogoPreview')">
                    <img id="editLogoPreview" src=""
                        class="mt-2 h-20 mx-auto object-contain rounded-lg hidden border border-gray-200 shadow-sm"
                        alt="Logo Preview">
                </div>
            </div>

            <!-- Upload QRIS -->
            <div>
                <label for="edit_qris" class="block text-sm font-medium text-gray-700 mb-1">Foto QRIS</label>
                <div
                    class="border border-dashed border-gray-300 rounded-xl p-3 text-center hover:border-indigo-400 transition">
                    <input type="file" id="edit_qris" name="qris" accept="image/*"
                        class="block w-full text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                        onchange="previewImage(event, 'editQrisPreview')">
                    <img id="editQrisPreview" src=""
                        class="mt-2 h-20 mx-auto object-contain rounded-lg hidden border border-gray-200 shadow-sm"
                        alt="QRIS Preview">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal('editBisnisModal')"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-md transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

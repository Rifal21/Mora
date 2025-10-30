<!-- Modal Tambah Bisnis -->
<div id="addBisnisModal"
    class="fixed inset-0 flex items-center justify-center bg-black/50 transition-opacity 
        {{ $errors->any() ? '' : 'opacity-0 pointer-events-none' }}">
    <div class="bg-white rounded-lg w-full max-w-md max-h-[70vh] md:max-h-[75vh] p-6 relative overflow-auto">
        <h3 class="font-bold text-lg mb-4">Tambah Bisnis</h3>

        {{-- Notifikasi error global --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                <strong>Terjadi kesalahan!</strong>
                <ul class="list-disc list-inside text-sm mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('bisnis.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Bisnis <span
                        class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('alamat') border-red-500 @enderror">
                @error('alamat')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <div class="w-1/2">
                    <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" id="telepon" name="telepon" value="{{ old('telepon') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('telepon') border-red-500 @enderror">
                    @error('telepon')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-1/2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Upload Logo -->
            <div>
                <label for="logo" class="block text-sm font-semibold text-gray-700 mb-1">Logo Bisnis</label>

                <div
                    class="relative border-2 border-dashed rounded-lg p-4 text-center cursor-pointer transition-all duration-200
               hover:border-indigo-400 hover:bg-indigo-50 
               {{ $errors->has('logo') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <input type="file" id="logo" name="logo" accept="image/*"
                        class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(event, 'logoPreview')">

                    <div class="flex flex-col items-center justify-center pointer-events-none">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-indigo-500 mb-2"></i>
                        <p class="text-sm text-gray-600">Klik untuk mengunggah <span class="font-medium">Logo
                                Bisnis</span></p>
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, max 2MB</p>
                    </div>
                </div>

                <img id="logoPreview" src=""
                    class="mt-3 h-24 mx-auto rounded-lg border border-gray-200 shadow-sm hidden" alt="Logo Preview">

                @error('logo')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload QRIS -->
            <div class="mt-4">
                <label for="qris" class="block text-sm font-semibold text-gray-700 mb-1">Foto QRIS</label>

                <div
                    class="relative border-2 border-dashed rounded-lg p-4 text-center cursor-pointer transition-all duration-200
               hover:border-indigo-400 hover:bg-indigo-50 
               {{ $errors->has('qris') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <input type="file" id="qris" name="qris" accept="image/*"
                        class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(event, 'qrisPreview')">

                    <div class="flex flex-col items-center justify-center pointer-events-none">
                        <i class="fa-solid fa-qrcode text-3xl text-indigo-500 mb-2"></i>
                        <p class="text-sm text-gray-600">Klik untuk mengunggah <span class="font-medium">Foto
                                QRIS</span></p>
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, max 2MB</p>
                    </div>
                </div>

                <img id="qrisPreview" src=""
                    class="mt-3 h-24 mx-auto rounded-lg border border-gray-200 shadow-sm hidden" alt="QRIS Preview">

                @error('qris')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>


            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('addBisnisModal')" class="px-4 py-2 rounded-lg border">
                    Batal
                </button>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

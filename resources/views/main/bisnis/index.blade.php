@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10 mt-24">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-store text-indigo-600"></i> Bisnis Saya
            </h1>
            <button onclick="openModal('addBisnisModal')"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fa-solid fa-plus"></i> Tambah Bisnis
            </button>
        </div>

        <!-- Grid bisnis -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($bisnisList as $bisnis)
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 p-6 relative">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fa-solid fa-briefcase text-indigo-500"></i> {{ $bisnis->name }}
                        </h2>
                        <span
                            class="px-2 py-1 text-xs rounded-full 
                        {{ $bisnis->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ ucfirst($bisnis->status) }}
                        </span>
                    </div>

                    @if ($bisnis->logo)
                        <img src="{{ asset('storage/' . $bisnis->logo) }}" alt="Logo"
                            class="h-20 object-contain mb-3 mx-auto">
                    @endif

                    <p class="text-sm text-gray-600 mb-2"><i class="fa-solid fa-location-dot text-indigo-500"></i>
                        {{ $bisnis->alamat ?? '-' }}</p>
                    <p class="text-sm text-gray-600 mb-2"><i class="fa-solid fa-phone text-indigo-500"></i>
                        {{ $bisnis->telepon ?? '-' }}</p>
                    <p class="text-sm text-gray-600 mb-2"><i class="fa-solid fa-envelope text-indigo-500"></i>
                        {{ $bisnis->email ?? '-' }}</p>

                    <div class="flex justify-end mt-4 gap-2">
                        <button onclick="openEditModal({{ $bisnis }})" class="text-blue-600 hover:text-blue-800">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('bisnis.destroy', $bisnis->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin menghapus bisnis ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-600">Belum ada bisnis. Tambahkan bisnis baru!</p>
            @endforelse
        </div>
        @if (auth()->check() && auth()->user()->role->name === 'Super Admin')
            {{-- table bisnis --}}
            <div class="mt-8 overflow-auto">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-store text-indigo-600"></i> Data Bisnis
                </h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Bisnis
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Pemilik
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Alamat
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Telepon
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($bisnisAdminList as $bisnis)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $bisnis->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $bisnis->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $bisnis->alamat }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $bisnis->telepon }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $bisnis->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $bisnis->status }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="openEditModal({{ $bisnis }})"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('bisnis.destroy', $bisnis->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus bisnis ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="">
                    {{ $bisnisAdminList->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Tambah Bisnis -->
    <div id="addBisnisModal"
        class="fixed inset-0 flex items-center justify-center bg-black/50 opacity-0 pointer-events-none transition-opacity">
        <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
            <h3 class="font-bold text-lg mb-4">Tambah Bisnis</h3>
            <form method="POST" action="{{ route('bisnis.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Bisnis</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <input type="text" id="alamat" name="alamat"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex gap-3">
                    <div class="w-1/2">
                        <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                        <input type="text" id="telepon" name="telepon"
                            class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="w-1/2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email"
                            class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Upload Logo -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" id="logo" name="logo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-600" onchange="previewImage(event, 'logoPreview')">
                    <img id="logoPreview" src="" class="mt-2 h-20 object-contain hidden" alt="Logo Preview">
                </div>

                <!-- Upload QRIS -->
                <div>
                    <label for="qris" class="block text-sm font-medium text-gray-700">Foto QRIS</label>
                    <input type="file" id="qris" name="qris" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-600" onchange="previewImage(event, 'qrisPreview')">
                    <img id="qrisPreview" src="" class="mt-2 h-20 object-contain hidden" alt="QRIS Preview">
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeModal('addBisnisModal')"
                        class="px-4 py-2 rounded-lg border">Batal</button>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Bisnis -->
    <div id="editBisnisModal"
        class="fixed inset-0 flex items-center justify-center bg-black/50 opacity-0 pointer-events-none transition-opacity">
        <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
            <h3 class="font-bold text-lg mb-4">Edit Bisnis</h3>
            <form id="editBisnisForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Bisnis</label>
                    <input type="text" id="edit_name" name="name"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
                <div>
                    <label for="edit_alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <input type="text" id="edit_alamat" name="alamat"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex gap-3">
                    <div class="w-1/2">
                        <label for="edit_telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                        <input type="text" id="edit_telepon" name="telepon"
                            class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="w-1/2">
                        <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="edit_email" name="email"
                            class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Upload Logo -->
                <div>
                    <label for="edit_logo" class="block text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" id="edit_logo" name="logo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-600" onchange="previewImage(event, 'editLogoPreview')">
                    <img id="editLogoPreview" src="" class="mt-2 h-20 object-contain hidden" alt="Logo Preview">
                </div>

                <!-- Upload QRIS -->
                <div>
                    <label for="edit_qris" class="block text-sm font-medium text-gray-700">Foto QRIS</label>
                    <input type="file" id="edit_qris" name="qris" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-600" onchange="previewImage(event, 'editQrisPreview')">
                    <img id="editQrisPreview" src="" class="mt-2 h-20 object-contain hidden" alt="QRIS Preview">
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeModal('editBisnisModal')"
                        class="px-4 py-2 rounded-lg border">Batal</button>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function openEditModal(bisnis) {
            document.getElementById('edit_name').value = bisnis.name;
            document.getElementById('edit_alamat').value = bisnis.alamat ?? '';
            document.getElementById('edit_telepon').value = bisnis.telepon ?? '';
            document.getElementById('edit_email').value = bisnis.email ?? '';

            // Set logo preview
            if (bisnis.logo) {
                document.getElementById('editLogoPreview').src = `/storage/${bisnis.logo}`;
                document.getElementById('editLogoPreview').classList.remove('hidden');
            } else {
                document.getElementById('editLogoPreview').classList.add('hidden');
            }

            // Set QRIS preview
            if (bisnis.qris) {
                document.getElementById('editQrisPreview').src = `/storage/${bisnis.qris}`;
                document.getElementById('editQrisPreview').classList.remove('hidden');
            } else {
                document.getElementById('editQrisPreview').classList.add('hidden');
            }

            // Set form action
            document.getElementById('editBisnisForm').action = `/bisnis/${bisnis.id}`;

            openModal('editBisnisModal');
        }

        function previewImage(event, previewId) {
            const input = event.target;
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.classList.remove('hidden');
            } else {
                preview.src = '';
                preview.classList.add('hidden');
            }
        }
    </script>
@endsection

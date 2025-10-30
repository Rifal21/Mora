@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10 mt-24">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-store text-indigo-600"></i> Bisnis
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
                        <form action="{{ route('bisnis.destroy', $bisnis->id) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="text-red-600 hover:text-red-800 delete-btn">
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
                                Logo
                            </th>
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
                                    @if ($bisnis->logo)
                                        <img src="{{ asset('storage/' . $bisnis->logo) }}" alt="Logo"
                                            class="h-10 object-contain">
                                    @else
                                        <div class="flex justify-center items-center h-10 w-10">
                                            <i class="fa-solid fa-store text-indigo-600 text-center"></i>
                                        </div>
                                    @endif
                                </td>
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
                                <td class="px-6 py-4">
                                    <button onclick="openEditModal({{ $bisnis }})"
                                        class="text-blue-600 hover:text-blue-800">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('bisnis.destroy', $bisnis->id) }}" method="POST"
                                        class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-800 delete-btn">
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

    @include('main.bisnis.create')

    @include('main.bisnis.edit')

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
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    preview.classList.add('animate-fadeIn');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('.delete-form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data bisnis yang dihapus tidak dapat dikembalikan.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.25s ease-out;
        }
    </style>
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const modal = document.getElementById('addBisnisModal');
                modal.classList.remove('opacity-0', 'pointer-events-none');
            });
        </script>
    @endif
@endsection

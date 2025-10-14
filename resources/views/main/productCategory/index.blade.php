@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10 mt-24">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Kategori Produk</h1>
            <button onclick="openModal('addCategoryModal')"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
            </button>
        </div>

        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-center">Gambar</th>
                        <th class="px-6 py-3 text-center">Bisnis</th>
                        <th class="px-6 py-3 text-center">Nama Kategori</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($categories as $cat)
                        <tr class="hover:bg-gray-50 transition text-center">
                            <td class="px-6 py-4">
                                @if ($cat->image)
                                    <img src="{{ asset('storage/' . $cat->image) }}"
                                        class="h-12 w-12 rounded-full object-cover mx-auto" alt="Gambar">
                                @else
                                    <div
                                        class="h-12 w-12 bg-gray-200 rounded-full mx-auto flex items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">{{ $cat->bisnis->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $cat->name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold 
                        {{ $cat->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($cat->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex justify-center items-center gap-4">
                                <button
                                    onclick="openEditModal({ 
                            id: '{{ $cat->id }}',
                            bisnis_id: '{{ $cat->bisnis_id }}',
                            name: '{{ addslashes($cat->name) }}',
                            status: '{{ $cat->status }}',
                            image: '{{ $cat->image ?? '' }}'
                        })"
                                    class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('product-categories.destroy', $cat->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800"><i
                                            class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada kategori produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('main.productCategory.modals')

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function openEditModal(category) {
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_bisnis_id').value = category.bisnis_id;
            document.getElementById('edit_status').value = category.status;

            const preview = document.getElementById('editImagePreview');
            if (category.image) {
                preview.src = '/storage/' + category.image;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }

            document.getElementById('editCategoryForm').action = `/product-categories/${category.id}`;
            openModal('editCategoryModal');
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

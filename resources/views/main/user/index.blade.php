@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10 mt-24">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">👥 Daftar Seluruh User</h1>
            <button onclick="openModal('addUserModal')"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah User
            </button>
        </div>

        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">No</th>
                        <th class="px-6 py-3 text-left">Avatar</th>
                        <th class="px-6 py-3 text-left">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left">Username</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Tipe User</th>
                        <th class="px-6 py-3 text-left">Quota AI</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                @if ($user->profile->avatar)
                                    <img class="w-10 h-10 rounded-full object-cover"
                                        src="{{ asset('storage/' . $user->profile->avatar) }}" alt="User avatar">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-user text-lg"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $user->profile->full_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $user->username }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->role->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $user->profile->user_type === 'pro' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($user->profile->user_type ?? '-') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->profile->quota_ai ?? 0 }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-3">
                                <button class="view-user-btn" data-name="{{ $user->profile->full_name ?? '-' }}"
                                    data-email="{{ $user->email }}" data-username="{{ $user->username }}"
                                    data-role="{{ $user->role->name ?? '-' }}"
                                    data-user_type="{{ $user->profile->user_type ?? 'free' }}"
                                    data-quota_ai="{{ $user->profile->quota_ai ?? 0 }}" data-status="{{ $user->status }}">
                                    <i class="fa-solid fa-eye text-gray-600"></i>
                                </button>

                                <button class="edit-user-btn" data-id="{{ $user->id }}"
                                    data-name="{{ $user->profile->full_name ?? $user->name }}"
                                    data-email="{{ $user->email }}" data-username="{{ $user->username }}"
                                    data-role_id="{{ $user->role_id }}"
                                    data-user_type="{{ $user->profile->user_type ?? 'free' }}"
                                    data-quota_ai="{{ $user->profile->quota_ai ?? 0 }}" data-status="{{ $user->status }}">
                                    <i class="fa-solid fa-pen text-blue-500"></i>
                                </button>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
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
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada user yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 🔹 Modal Tambah User --}}
    <div id="addUserModal"
        class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white/90 rounded-2xl shadow-2xl w-full max-w-md p-6 modal-enter border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-indigo-500"></i> Tambah User Baru
            </h2>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="full_name"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-400 outline-none transition"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-400 outline-none transition"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                    <input type="text" name="username"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-400 outline-none transition"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-400 outline-none transition"
                        required>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('addUserModal')"
                        class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-sm transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🔹 Modal Edit User --}}
    <div id="editUserModal"
        class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white/90 rounded-2xl shadow-2xl w-full max-w-md p-6 modal-enter border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-blue-500"></i> Edit User
            </h2>

            <form id="editUserForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="full_name" id="edit_full_name"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="edit_email"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" id="edit_username"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                    <select name="role_id" id="edit_role_id"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none transition">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe User</label>
                        <select name="user_type" id="edit_user_type"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none transition">
                            <option value="free">Free</option>
                            <option value="pro">Pro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Quota AI</label>
                        <input type="number" name="quota_ai" id="edit_quota_ai"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" id="edit_status"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none transition">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('editUserModal')"
                        class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-sm transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🔹 Modal View User --}}
    <div id="viewUserModal"
        class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white/90 rounded-2xl shadow-2xl w-full max-w-md p-6 modal-enter border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-gray-500"></i> Detail User
            </h2>
            <div id="viewUserContent" class="space-y-3 text-gray-700"></div>
            <div class="mt-5 text-right">
                <button onclick="closeModal('viewUserModal')"
                    class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Edit Button
        document.querySelectorAll('.edit-user-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                openModal('editUserModal');
                document.getElementById('editUserForm').action = `/users/${btn.dataset.id}`;
                document.getElementById('edit_full_name').value = btn.dataset.name;
                document.getElementById('edit_email').value = btn.dataset.email;
                document.getElementById('edit_username').value = btn.dataset.username;
                document.getElementById('edit_role_id').value = btn.dataset.role_id;
                document.getElementById('edit_user_type').value = btn.dataset.user_type;
                document.getElementById('edit_quota_ai').value = btn.dataset.quota_ai;
                document.getElementById('edit_status').value = btn.dataset.status;
            });
        });

        // View Button
        document.querySelectorAll('.view-user-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                openModal('viewUserModal');
                document.getElementById('viewUserContent').innerHTML = `
                <p><strong>Nama Lengkap:</strong> ${btn.dataset.name}</p>
                <p><strong>Email:</strong> ${btn.dataset.email}</p>
                <p><strong>Username:</strong> ${btn.dataset.username}</p>
                <p><strong>Role:</strong> ${btn.dataset.role}</p>
                <p><strong>Tipe User:</strong> ${btn.dataset.user_type}</p>
                <p><strong>Quota AI:</strong> ${btn.dataset.quota_ai}</p>
                <p><strong>Status:</strong> ${btn.dataset.status}</p>
            `;
            });
        });
    </script>
    <style>
        .modal-enter {
            animation: fadeIn 0.2s ease-out;
        }

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
    </style>
@endsection

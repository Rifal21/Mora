<section class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <header class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-blue-600"></i> Update Profile
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Manage and update your account information below.
            </p>
        </div>
        <div
            class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full text-sm font-medium">
            <i class="fa-solid fa-crown"></i>
            {{ ucfirst(Auth::user()->profile->user_type ?? 'Free') }}
        </div>
    </header>

    <!-- Success Message -->
    {{-- @if (session('status') === 'profile-updated')
        <div
            class="mb-4 text-green-600 dark:text-green-400 font-medium flex items-center gap-2 bg-green-50 dark:bg-green-900/40 p-3 rounded-lg">
            <i class="fa-solid fa-circle-check"></i> Profile updated successfully.
        </div>
    @endif --}}

    <!-- Form -->
    <form action="{{ route('my-profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Avatar -->
        <div class="flex items-center gap-5">
            <div class="relative">
                <img id="avatarPreview"
                    class="h-20 w-20 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600 shadow"
                    src="{{ Auth::user()->profile->avatar
                        ? asset('storage/' . Auth::user()->profile->avatar)
                        : 'https://cdn.flyonui.com/fy-assets/avatar/avatar-1.png' }}"
                    alt="{{ Auth::user()->profile->full_name ?? Auth::user()->name }}">
                <label for="avatar"
                    class="absolute bottom-0 right-0 bg-blue-600 text-white p-1.5 rounded-full cursor-pointer hover:bg-blue-700 shadow">
                    <i class="fa-solid fa-camera text-xs"></i>
                </label>
                <input id="avatar" name="avatar" type="file" class="hidden" accept="image/*"
                    onchange="previewAvatar(event)">
            </div>
            <div>
                <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</h3>
                <p class="text-sm text-gray-500">Click the camera icon to change your photo</p>
            </div>
        </div>

        <!-- Full Name -->
        <div>
            <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <i class="fa-solid fa-id-card mr-1 text-blue-600"></i> Full Name
            </label>
            <input id="full_name" name="full_name" type="text"
                value="{{ old('full_name', Auth::user()->profile->full_name ?? '') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100"
                required>
        </div>

        <!-- Gender -->
        <div>
            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <i class="fa-solid fa-venus-mars mr-1 text-blue-600"></i> Gender
            </label>
            <select id="gender" name="gender"
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Select Gender</option>
                <option value="male"
                    {{ old('gender', Auth::user()->profile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female"
                    {{ old('gender', Auth::user()->profile->gender ?? '') == 'female' ? 'selected' : '' }}>Female
                </option>
            </select>
        </div>

        <!-- Birth Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="place_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-location-dot mr-1 text-blue-600"></i> Place of Birth
                </label>
                <input id="place_of_birth" name="place_of_birth" type="text"
                    value="{{ old('place_of_birth', Auth::user()->profile->place_of_birth ?? '') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100">
            </div>
            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-calendar-day mr-1 text-blue-600"></i> Date of Birth
                </label>
                <input id="birth_date" name="birth_date" type="date"
                    value="{{ old('birth_date', Auth::user()->profile->birth_date ?? '') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100">
            </div>
        </div>

        <!-- Contact Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-phone mr-1 text-blue-600"></i> Phone Number
                </label>
                <input id="phone_number" name="phone_number" type="text"
                    value="{{ old('phone_number', Auth::user()->profile->phone_number ?? '') }}" placeholder="+62..."
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-map-location-dot mr-1 text-blue-600"></i> Address
                </label>
                <input id="address" name="address" type="text"
                    value="{{ old('address', Auth::user()->profile->address ?? '') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100">
            </div>
        </div>

        <!-- Identity Numbers -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="no_ktp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-id-card-clip mr-1 text-blue-600"></i> No. KTP
                </label>
                <input id="no_ktp" name="no_ktp" type="text"
                    value="{{ old('no_ktp', Auth::user()->profile->no_ktp ?? '') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100">
            </div>
            <div>
                <label for="no_npwp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-file-invoice-dollar mr-1 text-blue-600"></i> No. NPWP
                </label>
                <input id="no_npwp" name="no_npwp" type="text"
                    value="{{ old('no_npwp', Auth::user()->profile->no_npwp ?? '') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100">
            </div>
        </div>

        <!-- Religion -->
        <div>
            <label for="religion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <i class="fa-solid fa-hands-praying mr-1 text-blue-600"></i> Religion
            </label>
            <input id="religion" name="religion" type="text"
                value="{{ old('religion', Auth::user()->profile->religion ?? '') }}"
                placeholder="e.g., Islam, Christian, Hindu..."
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100">
        </div>

        <!-- Save Button -->
        <div class="flex justify-end pt-2">
            <button type="submit"
                class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg gap-2 shadow transition">
                <i class="fa-solid fa-floppy-disk"></i> Save Changes
            </button>
        </div>
    </form>
</section>

<!-- Avatar Preview Script -->
<script>
    function previewAvatar(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('avatarPreview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

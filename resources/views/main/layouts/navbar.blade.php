<!-- Floating Navbar (Desktop Only) -->
<nav
    class="hidden xl:flex fixed top-6 left-1/2 -translate-x-1/2 w-[90%] sm:w-[80%] z-50 
           bg-white/80 backdrop-blur-lg border border-white/80 
           shadow-[0_8px_32px_rgba(31,38,135,0.37)] 
           rounded-full px-6 py-3 items-center justify-between transition-all duration-300">

    <!-- Left: Dropdown menu -->
    <div class="relative">
        <button id="menuButton"
            class="p-2 rounded-full hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/30"
            aria-haspopup="true" aria-expanded="false" aria-label="Open menu">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        <ul id="menuDropdown"
            class="hidden absolute mt-3 w-56 bg-white/80 backdrop-blur-md border border-gray-200/40 rounded-xl shadow-lg">
            <li><a href="/" class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                        class="fa-solid fa-house mr-2"></i>Home</a></li>
            <li><a href="{{ route('bisnis.index') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                        class="fa-solid fa-store mr-2"></i>Bisnis</a></li>
            <li><a href="{{ route('product-categories.index') }}"
                    class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                        class="fa-solid fa-list mr-2"></i>Kategori
                    Produk</a></li>
            <li><a href="{{ route('products.index') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                        class="fa-solid fa-box mr-2"></i>Produk</a></li>
            <hr class="border-gray-200 my-1">
            <li><a href="{{ route('ai.chat') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                        class="fa-solid fa-robot mr-2"></i>Mora AI</a></li>
            <li><a href="{{ route('transactions.index') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                        class="fa-solid fa-cash-register mr-2"></i>Mora POS</a></li>
            <li><a href="{{ route('blogPosts.index') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                        class="fa-solid fa-link mr-2"></i>Trends</a></li>
            <li><a href="{{ route('transactions.list') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                        class="fa-solid fa-money-check mr-2"></i>Catatan Keuangan</a></li>
            @if (auth()->check() && auth()->user()->role->name === 'Super Admin')
                <hr class="border-gray-200 my-1">
                <li><a href="{{ route('blogCategories.index') }}"
                        class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                            class="fa-solid fa-list-check mr-2"></i>Kategori Artikel</a></li>
                <li><a href="{{ route('blogPosts.list') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                            class="fa-solid fa-newspaper mr-2"></i>Artikel</a></li>
                <li><a href="{{ route('users.index') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-800"><i
                            class="fa-solid fa-users mr-2"></i>Users</a></li>
                <a href="{{ route('plans.index') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                    <i class="fa-solid fa-crown mr-2"></i> Plans
                </a>
            @endif
        </ul>
    </div>

    <!-- Center: Brand -->
    <div class="text-xl font-bold drop-shadow">
        <a href="/" class="no-underline hover:text-gray-200 transition">
            <img src="{{ asset('assets/images/logo mora.png') }}" class="w-20 h-12 object-cover" alt="Mora Logo">
        </a>
    </div>

    <!-- Right Side -->
    <div class="flex items-center space-x-4">
        <!-- User Type Badge -->
        @auth
            <div class="p-2 rounded-full focus:ring-2 focus:ring-white/30 flex items-center space-x-4 ">
                @if (Auth::user()->profile->user_type === 'pro')
                    <div
                        class="badge bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-md py-1 px-2 rounded-lg">
                        <i class="fa-solid fa-crown mr-1"></i> Pro
                    </div>
                @else
                    <div class="badge bg-sky-500 text-white font-semibold shadow-md py-1 px-2 rounded-lg">
                        <i class="fa-solid fa-leaf mr-1"></i> Free
                    </div>
                    <a href="{{ route('cart.index') }}" class="relative">
                        <i class="fa-solid fa-shopping-cart text-xl"></i>
                        @if (session('cart'))
                            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full px-2">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                @endif
            </div>
        @endauth

        <!-- Profile Dropdown -->
        @auth
            <div class="relative">
                <button id="profileButton"
                    class="flex items-center focus:outline-none focus:ring-2 focus:ring-white/30 rounded-full"
                    aria-haspopup="true" aria-expanded="false" aria-label="Profile menu">
                    @if (Auth::user()->profile && Auth::user()->profile->avatar)
                        <img class="h-10 w-10 rounded-full mr-3 ring-1 ring-gray-300 object-cover"
                            src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="User avatar">
                    @else
                        <div
                            class="h-10 w-10 rounded-full mr-3 ring-1 ring-gray-300 bg-gray-200 flex items-center justify-center text-gray-500">
                            <i class="fa-solid fa-user text-lg"></i>
                        </div>
                    @endif
                </button>

                <ul id="profileDropdown"
                    class="hidden absolute right-0 mt-3 w-64 bg-white/90 backdrop-blur-md border border-gray-200/40 rounded-xl shadow-lg">
                    <li class="flex items-center px-4 py-3 border-b border-gray-100/40">
                        @if (Auth::user()->profile && Auth::user()->profile->avatar)
                            <img class="h-10 w-10 rounded-full mr-3 ring-1 ring-gray-300 object-cover"
                                src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="User avatar">
                        @else
                            <div
                                class="h-10 w-10 rounded-full mr-3 ring-1 ring-gray-300 bg-gray-200 flex items-center justify-center text-gray-500">
                                <i class="fa-solid fa-user text-lg"></i>
                            </div>
                        @endif

                        <div>
                            <p class="text-gray-800 font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                        </div>
                    </li>
                    <li><a href="{{ route('my-profile.index') }}"
                            class="block px-4 py-2 hover:bg-gray-100 text-gray-700"><i class="fa-solid fa-user mr-2"></i>My
                            Profile</a></li>
                    {{-- <li><a href="/settings" class="block px-4 py-2 hover:bg-gray-100 text-gray-700"><i
                                class="fa-solid fa-gear mr-2"></i>Settings</a></li> --}}
                    <li><a href="{{ route('billing.index') }}" class="block px-4 py-2 hover:bg-gray-100 text-gray-700"><i
                                class="fa-solid fa-wallet mr-2"></i>Billing</a></li>
                    {{-- <li><a href="/faqs" class="block px-4 py-2 hover:bg-gray-100 text-gray-700"><i
                                class="fa-solid fa-circle-question mr-2"></i>FAQs</a></li> --}}
                    <li class="border-t border-gray-100/40">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50 font-medium">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i>Sign out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth

        <!-- Guest -->
        @guest
            <div class="flex items-center space-x-3">
                <a href="{{ route('login') }}"
                    class="px-4 py-2 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition font-medium">
                    <i class="fa-solid fa-right-to-bracket mr-1"></i> Login
                </a>
            </div>
        @endguest
    </div>
</nav>

<!-- Mobile Header -->
<div
    class="xl:hidden fixed top-0 left-0 w-full flex justify-between items-center px-5 py-3  bg-white/20 border-b  z-50 backdrop-blur-lg border border-white/20 shadow-[0_8px_32px_rgba(31,38,135,0.37)] 
           rounded-xl transition-all duration-300 mt-3">
    <!-- Logo kiri -->
    <a href="/">
        <img src="{{ asset('assets/images/logo mora.png') }}" class="w-16 h-10 object-cover" alt="Mora Logo">
    </a>
    <!-- Badge kanan -->
    @auth
        @if (Auth::user()->profile->user_type === 'pro')
            <span
                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold py-1 px-2 rounded-lg shadow">
                <i class="fa-solid fa-crown mr-1"></i> Pro
            </span>
        @else
            <span class="bg-sky-500 text-white text-sm font-semibold py-1 px-2 rounded-lg shadow">
                <i class="fa-solid fa-leaf mr-1"></i> Free
            </span>
            <a href="{{ route('cart.index') }}" class="relative">
                <i class="fa-solid fa-shopping-cart text-xl"></i>
                @if (session('cart'))
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full px-2">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
        @endif
    @endauth
    @guest
        <span class="bg-blue-500 text-white text-sm font-semibold py-1 px-2 rounded-lg shadow">
            <a href="{{ route('login') }}">
                <i class="fa-solid fa-right-to-bracket mr-1"></i> Login
            </a>
        </span>
    @endguest
</div>


<!-- Mobile Bottom Nav -->
<div class="xl:hidden fixed bottom-0 left-0 w-full bg-white/80 backdrop-blur-lg border-t border-gray-200/50 z-50">
    <div class="flex justify-around py-2 text-gray-700 text-sm relative">

        <!-- Home -->
        <a href="/"
            class="flex flex-col items-center {{ request()->is('/') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
            <div
                class="{{ request()->is('/') ? 'border-t-2 border-blue-600' : 'border-t-2 border-transparent' }} w-10 mb-1 transition-all">
            </div>
            <i class="fa-solid fa-house text-lg"></i>
            <span>Home</span>
        </a>

        <!-- Bisnis -->
        <a href="{{ route('ai.chat') }}"
            class="flex flex-col items-center {{ request()->is('konsultasi*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
            <div
                class="{{ request()->is('konsultasi*') ? 'border-t-2 border-blue-600' : 'border-t-2 border-transparent' }} w-10 mb-1 transition-all">
            </div>
            <i class="fa-solid fa-robot text-lg"></i>
            <span>Mora AI</span>
        </a>


        <!-- Settings (Menonjol) -->
        {{-- <a href="/settings"
            class="flex flex-col items-center -mt-5 text-gray-600 hover:text-blue-600 transition relative">
            <div
                class="w-14 h-14 bg-yellow-500 text-white rounded-full flex items-center justify-center shadow-lg -mb-2">
                <i class="fas fa-paw text-xl"></i>
            </div>
            <span class="mt-2.5 text-xs">Mora Apps</span>
        </a> --}}
        <div class="flex flex-col items-center -mt-7 text-gray-600 hover:text-blue-600 transition relative">
            <button id="mobileMoraBtn"
                class="w-14 h-14 bg-blue-500 text-white rounded-full flex flex-col items-center justify-center shadow-lg -mb-2">
                <div class="w-20 mb-1 transition-all"></div>
                <i class="fas fa-paw text-xl"></i>
            </button>
            <span class="mt-3">Mora Apps</span>
            <!-- Dropdown -->
            <div id="mobileMoraDropdown"
                class="hidden absolute bottom-24  bg-white/90 backdrop-blur-md border border-gray-200/40 rounded-xl shadow-lg w-48 flex flex-col text-gray-700">
                <a href="{{ route('transactions.index') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                    <i class="fa-solid fa-cash-register mr-2"></i> Mora POS
                </a>
                <a href="{{ route('transactions.list') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                    <i class="fa-solid fa-money-check mr-2"></i> Catatan Keuangan
                </a>
                <a href="{{ route('bisnis.index') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                    <i class="fa-solid fa-store mr-2"></i> Bisnis
                </a>
                <a href="{{ route('product-categories.index') }}"
                    class="px-4 py-2 hover:bg-gray-100 flex items-center">
                    <i class="fa-solid fa-list mr-2"></i> Kategori Produk
                </a>
                <a href="{{ route('products.index') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                    <i class="fa-solid fa-box mr-2"></i> Produk
                </a>
                @if (auth()->check() && auth()->user()->role->name === 'Super Admin')
                    <a href="{{ route('users.index') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                        <i class="fa-solid fa-users mr-2"></i> Users
                    </a>
                    <a href="{{ route('plans.index') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                        <i class="fa-solid fa-crown mr-2"></i> Plans
                    </a>
                @endif
            </div>
        </div>

        @if (auth()->check() && auth()->user()->role->name === 'Super Admin')
            <div class="relative flex flex-col items-center">
                <button id="mobileTrendsBtn" class="flex flex-col items-center focus:outline-none">
                    <div class="w-10 mb-1 transition-all"></div>
                    <i class="fa-solid fa-link text-lg"></i>
                    <span>Trends</span>
                </button>
                <!-- Dropdown -->
                <div id="mobileTrendsDropdown"
                    class="hidden absolute bottom-16 bg-white/90 backdrop-blur-md border border-gray-200/40 rounded-xl shadow-lg w-40 flex flex-col text-gray-700">
                    <a href="{{ route('blogPosts.index') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                        <i class="fa-solid fa-link mr-2"></i> Trends
                    </a>
                    <a href="{{ route('blogCategories.index') }}"
                        class="px-4 py-2 hover:bg-gray-100 flex items-center">
                        <i class="fa-solid fa-list-check mr-2"></i> Kategori Artikel
                    </a>
                    <a href="{{ route('blogPosts.list') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                        <i class="fa-solid fa-newspaper mr-2"></i> Artikel
                    </a>
                </div>
            </div>
        @else
            <a href="{{ route('blogPosts.index') }}"
                class="flex flex-col items-center {{ request()->is('bisnis*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                <div
                    class="{{ request()->is('bisnis*') ? 'border-t-2 border-blue-600' : 'border-t-2 border-transparent' }} w-10 mb-1 transition-all">
                </div>
                <i class="fa-solid fa-link  text-lg"></i>
                <span>Trends</span>
            </a>
        @endif

        <!-- Profile / Login -->
        @if (auth()->check())
            <div class="relative flex flex-col items-center">
                <button id="mobileProductsBtn" class="flex flex-col items-center focus:outline-none">
                    <div class="w-10 mb-1 transition-all"></div>
                    <i class="fa-solid fa-user text-lg"></i>
                    <span>Profile</span>
                </button>
                <!-- Dropdown -->
                <div id="mobileProductsDropdown"
                    class="hidden absolute bottom-16 bg-white/90 backdrop-blur-md border border-gray-200/40 rounded-xl shadow-lg w-40 flex flex-col text-gray-700">
                    <a href="{{ route('my-profile.index') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                        <i class="fa-solid fa-user mr-2"></i> My Profile
                    </a>
                    <a href="{{ route('billing.index') }}" class="px-4 py-2 hover:bg-gray-100 flex items-center">
                        <i class="fa-solid fa-wallet mr-2"></i> Billing
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50 font-medium">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i>Sign out
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="/login"
                class="flex flex-col items-center {{ request()->is('login*') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                <div
                    class="{{ request()->is('login*') ? 'border-t-2 border-blue-600' : 'border-t-2 border-transparent' }} w-10 mb-1 transition-all">
                </div>
                <i class="fa-solid fa-right-to-bracket text-lg"></i>
                <span>Login</span>
            </a>
        @endif

    </div>
</div>


<script>
    // Dropdown logic tetap sama
    const menuButton = document.getElementById('menuButton');
    const menuDropdown = document.getElementById('menuDropdown');
    const profileButton = document.getElementById('profileButton');
    const profileDropdown = document.getElementById('profileDropdown');
    // Mobile Products Dropdown
    const mobileProductsBtn = document.getElementById('mobileProductsBtn');
    const mobileProductsDropdown = document.getElementById('mobileProductsDropdown');
    const mobileTrendsBtn = document.getElementById('mobileTrendsBtn');
    const mobileTrendsDropdown = document.getElementById('mobileTrendsDropdown');
    const mobileMoraBtn = document.getElementById('mobileMoraBtn');
    const mobileMoraDropdown = document.getElementById('mobileMoraDropdown');

    if (mobileProductsBtn) {
        mobileProductsBtn.addEventListener('click', () => {
            mobileProductsDropdown.classList.toggle('hidden');
        });
    }

    if (mobileMoraBtn) {
        mobileMoraBtn.addEventListener('click', () => {
            mobileMoraDropdown.classList.toggle('hidden');
        });
    }

    if (mobileTrendsBtn) {
        mobileTrendsBtn.addEventListener('click', () => {
            mobileTrendsDropdown.classList.toggle('hidden');
        });
    }

    // Click outside to close
    window.addEventListener('click', (e) => {
        if (mobileProductsDropdown && !mobileProductsBtn.contains(e.target) && !mobileProductsDropdown.contains(
                e.target)) {
            mobileProductsDropdown.classList.add('hidden');
        }
    });

    if (menuButton) menuButton.addEventListener('click', () => menuDropdown.classList.toggle('hidden'));
    if (profileButton) profileButton.addEventListener('click', () => profileDropdown.classList.toggle('hidden'));

    window.addEventListener('click', (e) => {
        if (menuDropdown && !menuButton.contains(e.target) && !menuDropdown.contains(e.target)) menuDropdown
            .classList.add('hidden');
        if (profileDropdown && !profileButton.contains(e.target) && !profileDropdown.contains(e.target))
            profileDropdown.classList.add('hidden');
    });
</script>

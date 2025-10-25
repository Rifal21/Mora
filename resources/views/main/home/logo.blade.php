@guest
    <!-- Carousel Logo -->
    <section class="container mx-auto px-4 py-20">
        <div class="text-center mb-10">
            <h2
                class="font-extrabold text-3xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
                Dipercaya Oleh Banyak Bisnis
            </h2>
            <p class="text-gray-500 text-lg">
                Dari UMKM hingga bisnis berkembang, mereka semua menggunakan
                <span class="font-semibold text-indigo-600">Mora Finance</span>.
            </p>
        </div>

        <div class="swiper logo-swiper">
            <div class="swiper-wrapper items-center">
                @foreach ($bisnis as $item)
                    <div class="swiper-slide flex justify-center items-center">
                        @if ($item->logo && file_exists(public_path('storage/' . $item->logo)))
                            <img src="{{ asset('storage/' . $item->logo) }}"
                                class="h-24 w-auto opacity-70 hover:opacity-100 transition object-contain"
                                alt="{{ $item->name }}">
                        @else
                            <div
                                class="flex items-center justify-center h-24 w-24 bg-gradient-to-r from-indigo-100 to-blue-50 rounded-xl shadow-sm">
                                <i class="fa-solid fa-building text-4xl text-indigo-500"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endguest

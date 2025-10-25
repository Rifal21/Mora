 <!-- Berita Terbaru -->
 <section class="container mx-auto px-4 py-10">
     <div class="text-center mb-5">
         <h2
             class="font-extrabold text-4xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
             Berita Terbaru
         </h2>
         <p class="text-gray-500 text-lg">
             Dapatkan insight, tips, dan berita terbaru seputar bisnis & keuangan.
         </p>
     </div>

     <!-- Swiper untuk mobile -->
     <div class="swiper news-swiper md:hidden">
         <div class="swiper-wrapper">
             @foreach ($news as $item)
                 <div class="swiper-slide p-3">
                     <div
                         class="group bg-white rounded-3xl shadow-md overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                         @if ($item->image)
                             <div class="relative">
                                 <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                     class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                                 <div
                                     class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition">
                                 </div>
                             </div>
                         @endif
                         <div class="p-6">
                             <p class="text-sm text-gray-400 mb-2">
                                 {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                             </p>
                             <h3
                                 class="font-bold text-xl text-gray-800 mb-3 group-hover:text-indigo-600 transition line-clamp-2">
                                 {{ $item->title }}
                             </h3>
                             <p class="text-gray-600 text-sm mb-5 line-clamp-3">
                                 {{ Str::limit(strip_tags($item->content), 100) }}
                             </p>
                             <a href="{{ route('blogPosts.read', $item->slug) }}"
                                 class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition">
                                 Baca Selengkapnya
                                 <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                     <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                 </svg>
                             </a>
                         </div>
                     </div>
                 </div>
             @endforeach
         </div>
     </div>

     <!-- Grid versi desktop -->
     <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
         @foreach ($news as $item)
             <div
                 class="group bg-white rounded-3xl shadow-md overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                 @if ($item->image)
                     <div class="relative">
                         <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                             class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                         <div
                             class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition">
                         </div>
                     </div>
                 @endif
                 <div class="p-6">
                     <p class="text-sm text-gray-400 mb-2">
                         {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                     </p>
                     <h3
                         class="font-bold text-xl text-gray-800 mb-3 group-hover:text-indigo-600 transition line-clamp-2">
                         {{ $item->title }}
                     </h3>
                     <p class="text-gray-600 text-sm mb-5 line-clamp-3">
                         {{ Str::limit(strip_tags($item->content), 100) }}
                     </p>
                     <a href="{{ route('blogPosts.read', $item->slug) }}"
                         class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition">
                         Baca Selengkapnya
                         <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                         </svg>
                     </a>
                 </div>
             </div>
         @endforeach
     </div>
 </section>

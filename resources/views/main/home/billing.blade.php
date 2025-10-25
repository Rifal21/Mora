 <!-- Paket Langganan -->
 <section class="container mx-auto px-4 py-10 mt-10">
     <div class="text-center mb-5">
         <h2
             class="font-extrabold text-4xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
             Pilih Paket Langganan
         </h2>
         <p class="text-gray-500 text-lg">
             Pilih paket sesuai kebutuhanmu dan nikmati semua fitur premium untuk bisnismu.
         </p>
     </div>

     <!-- Wrapper untuk Swiper -->
     <div class="swiper paket-swiper md:hidden">
         <div class="swiper-wrapper">
             @foreach ($billing as $package)
                 <div class="swiper-slide p-3">
                     <div
                         class="relative bg-white/70 backdrop-blur-xl rounded-3xl shadow-lg p-8 border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                         @if ($loop->first)
                             <span
                                 class="absolute top-5 right-5 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                 POPULER
                             </span>
                         @endif
                         <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                             {{ $package->name }}
                         </h3>
                         <p class="text-gray-500 mb-6 h-16">{{ Str::limit($package->description, 80) }}</p>

                         @php
                             $durationLabel =
                                 $package->duration_days < 30
                                     ? '/hari'
                                     : ($package->duration_days < 365
                                         ? '/bulan'
                                         : '/tahun');
                             $originalPrice = $package->price * 1.5;
                         @endphp

                         <div class="mb-6">
                             <p class="text-gray-400 text-lg line-through mb-1">
                                 Rp{{ number_format($originalPrice, 0, ',', '.') }}
                             </p>
                             <p class="text-4xl font-extrabold text-indigo-600">
                                 Rp{{ number_format($package->price, 0, ',', '.') }}
                                 <span class="text-gray-500 text-lg font-medium">{{ $durationLabel }}</span>
                             </p>
                         </div>

                         <form action="{{ route('cart.add') }}" method="POST">
                             @csrf
                             <input type="hidden" name="package_id" value="{{ $package->id }}">
                             <button type="submit"
                                 class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 
                           text-white py-3 rounded-full font-semibold shadow-md hover:shadow-indigo-400/40 transition-all">
                                 Tambahkan ke Keranjang
                             </button>
                         </form>
                     </div>
                 </div>
             @endforeach
         </div>
     </div>

     <!-- Versi Grid untuk Desktop -->
     <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-8">
         @foreach ($billing as $package)
             <div
                 class="relative bg-white/70 backdrop-blur-xl rounded-3xl shadow-lg p-8 border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                 @if ($loop->first)
                     <span
                         class="absolute top-5 right-5 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                         POPULER
                     </span>
                 @endif
                 <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                     {{ $package->name }}
                 </h3>
                 <p class="text-gray-500 mb-6 h-16">{{ Str::limit($package->description, 80) }}</p>

                 @php
                     $durationLabel =
                         $package->duration_days < 30 ? '/hari' : ($package->duration_days < 365 ? '/bulan' : '/tahun');
                     $originalPrice = $package->price * 1.5;
                 @endphp

                 <div class="mb-6">
                     <p class="text-gray-400 text-lg line-through mb-1">
                         Rp{{ number_format($originalPrice, 0, ',', '.') }}
                     </p>
                     <p class="text-4xl font-extrabold text-indigo-600">
                         Rp{{ number_format($package->price, 0, ',', '.') }}
                         <span class="text-gray-500 text-lg font-medium">{{ $durationLabel }}</span>
                     </p>
                 </div>

                 <form action="{{ route('cart.add') }}" method="POST">
                     @csrf
                     <input type="hidden" name="package_id" value="{{ $package->id }}">
                     <button type="submit"
                         class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 
                           text-white py-3 rounded-full font-semibold shadow-md hover:shadow-indigo-400/40 transition-all">
                         Tambahkan ke Keranjang
                     </button>
                 </form>
             </div>
         @endforeach
     </div>
 </section>

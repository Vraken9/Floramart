<x-app-layout>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Profil -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8 flex flex-col md:flex-row items-center gap-6">
                <div class="w-24 h-24 bg-[#7c4959] text-white rounded-full flex items-center justify-center text-4xl font-bold uppercase shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-center md:text-left flex-grow">
                    <h2 class="text-2xl font-extrabold text-gray-900">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-500 font-medium mt-1">{{ Auth::user()->email }}</p>
                    <div class="mt-3 inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold uppercase tracking-wider">
                        Role: {{ ucfirst(Auth::user()->role) }}
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex gap-3">
                    <a href="{{ route('profile.edit') }}" class="px-5 py-2.5 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:border-gray-300 hover:bg-gray-50 transition-all text-sm">
                        <i class="fa-solid fa-user-pen mr-2"></i> Edit Profil
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar Menu (Status Toko) -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                            <i class="fa-solid fa-store text-[#7c4959] mr-2"></i> Pendaftaran Toko
                        </h3>
                        
                        @if($userShop)
                            <div class="p-4 rounded-xl {{ $userShop->status == 'approved' ? 'bg-green-50 border border-green-100' : ($userShop->status == 'rejected' ? 'bg-red-50 border border-red-100' : 'bg-blue-50 border border-blue-100') }}">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status Pendaftaran</p>
                                <p class="font-extrabold text-lg mb-2
                                    {{ $userShop->status == 'approved' ? 'text-green-700' : ($userShop->status == 'rejected' ? 'text-red-700' : 'text-blue-700') }}">
                                    @if($userShop->status == 'approved')
                                        Aktif
                                    @elseif($userShop->status == 'rejected')
                                        Ditolak
                                    @else
                                        Sedang Diverifikasi
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600 mb-3 font-medium">Toko: <strong>{{ $userShop->name }}</strong></p>
                                
                                @if($userShop->status == 'approved')
                                    <a href="{{ route('owner.products.index') }}" class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 transition-colors">
                                        Masuk Dasbor Toko
                                    </a>
                                @elseif($userShop->status == 'rejected')
                                    <p class="text-xs text-red-600 mb-3">Alasan: {{ $userShop->rejected_reason ?? 'Tidak memenuhi syarat.' }}</p>
                                    <a href="https://wa.me/6281234567890" target="_blank" class="block w-full text-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition-colors">
                                        <i class="fa-brands fa-whatsapp"></i> Hubungi Admin
                                    </a>
                                @else
                                    <a href="https://wa.me/6281234567890" target="_blank" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors">
                                        <i class="fa-brands fa-whatsapp"></i> Hubungi Admin
                                    </a>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500 mb-4 leading-relaxed">Punya bisnis merangkai bunga? Bergabunglah sebagai mitra florist FloraMart dan jangkau lebih banyak pelanggan secara online.</p>
                            <a href="{{ route('shop.create') }}" class="block w-full text-center px-4 py-2.5 bg-[#7c4959] text-white rounded-xl text-sm font-bold hover:bg-[#5d3642] shadow-sm transition-colors">
                                <i class="fa-solid fa-shop mr-1"></i> Daftar Sebagai Owner
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Konten Utama (Favorit) -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-extrabold text-gray-900"><i class="fa-solid fa-heart text-red-500 mr-2"></i> Bunga Favorit Saya</h3>
                            <span class="bg-gray-100 text-gray-700 py-1 px-3 rounded-full text-sm font-bold">{{ $favoriteProducts->count() }} Disimpan</span>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($favoriteProducts as $product)
                                <div class="bg-white rounded-xl border border-[#d4ccc0]/40 overflow-hidden flex flex-col group hover:shadow-md transition-all duration-300 relative">
                                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                        @csrf
                                        <button type="submit" class="p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-sm hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        </button>
                                    </form>

                                    <div class="aspect-square bg-gray-50 overflow-hidden relative">
                                        @if(str_starts_with($product->image_path, 'http'))
                                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $product->image_path }}" alt="{{ $product->name }}">
                                        @else
                                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                                        @endif
                                        <span class="absolute top-3 left-3 px-2.5 py-1 text-xs font-bold tracking-wider uppercase bg-white/95 text-[#7c4959] rounded-sm shadow-sm">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                    <div class="p-4 flex-grow flex flex-col justify-between bg-white">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-1 group-hover:text-[#7c4959] transition-colors mb-1">{{ $product->name }}</h3>
                                            <div class="flex items-center text-xs text-gray-500 mb-3">
                                                <svg class="h-3.5 w-3.5 text-[#926a7a] mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                </svg>
                                                <a href="{{ route('shop.show', $product->shop->id) }}" class="hover:text-[#7c4959] hover:underline font-medium transition-colors">
                                                    {{ $product->shop->name }}
                                                </a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-base font-bold text-gray-900 mb-2">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </div>
                                            <a href="{{ route('product.show', $product->slug) }}" class="block w-full border border-[#d4ccc0]/50 text-gray-700 hover:border-[#7c4959] hover:text-[#7c4959] hover:bg-[#7c4959]/5 rounded-lg text-xs font-semibold py-2 transition-all text-center mt-2">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full bg-[#d4ccc0]/5 rounded-2xl p-12 text-center border border-[#d4ccc0]/30 shadow-sm">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white mb-4 shadow-sm border border-[#d4ccc0]/50">
                                        <svg class="w-8 h-8 text-[#926a7a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada bunga favorit</h3>
                                    <p class="text-gray-500 mb-6 text-sm">Temukan berbagai koleksi bunga terbaik dari perajin lokal.</p>
                                    <a href="{{ route('katalog.index') }}" class="inline-flex items-center px-5 py-2.5 bg-[#7c4959] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#5d3642] shadow-sm transition-all">
                                        Jelajahi Katalog
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 border-b border-[#d4ccc0]/40 pb-6">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Koleksi Bunga Favorit Anda</h2>
                <p class="text-sm text-gray-500 mt-2 leading-relaxed max-w-3xl">Daftar kusuma pilihan yang telah Anda simpan di ekosistem FloraMart. Hubungi pemilik Toko Florist via WhatsApp secara langsung kapan saja untuk memproses pesanan khusus Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
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
                                <img class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500" src="{{ $product->image_path }}" alt="{{ $product->name }}">
                            @else
                                <img class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
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
                    <div class="col-span-full bg-[#d4ccc0]/10 rounded-2xl p-16 text-center border border-[#d4ccc0]/30 shadow-sm">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white mb-6 shadow-sm border border-[#d4ccc0]/50">
                            <svg class="w-10 h-10 text-[#926a7a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2 tracking-tight">Belum ada bunga di daftar favorit Anda</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto text-sm">Temukan berbagai koleksi bunga terbaik dari perajin lokal pilihan kami dan simpan di sini.</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-[#7c4959] text-white rounded-lg text-sm font-bold uppercase tracking-wider hover:bg-[#5d3642] shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#7c4959]">
                            Jelajahi Beranda Utama
                        </a>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>

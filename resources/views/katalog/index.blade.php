@php
if (!function_exists('formatRupiah')) {
    function formatRupiah($angka){
        return 'Rp ' . number_format($angka,0,',','.');
    }
}
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Bunga - FloraMart</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-plum { background-color: #7c4959; }
        .text-plum { color: #7c4959; }
        .border-plum { border-color: #7c4959; }
        .bg-warm-beige { background-color: #d4ccc0; }
        .hover-bg-plum-dark:hover { background-color: #5d3642; }
        /* Mobile horizontal scroll filter chips */
        .filter-scroll::-webkit-scrollbar { display: none; }
        .filter-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <x-navigation />

        <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10 w-full">
        
        {{-- MOBILE: Collapsible Filter --}}
        <div class="md:hidden mb-4" x-data="{ filterOpen: false }">
            <button @click="filterOpen = !filterOpen" data-a11y="btn-filter-mobile" class="w-full flex items-center justify-between px-4 py-3 bg-white rounded-xl shadow-sm border border-gray-200 text-sm font-bold text-gray-700">
                <span><i class="fa-solid fa-sliders mr-2 text-[#7c4959]"></i> Filter & Pencarian</span>
                <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="filterOpen && 'rotate-180'"></i>
            </button>
            <div x-show="filterOpen" x-cloak x-transition class="mt-2 bg-white p-4 rounded-xl shadow-sm border border-gray-200" data-a11y="filter-area">
                <form method="GET" action="{{ route('katalog.index') }}" class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Nama Bunga</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: Mawar..." class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#7c4959] focus:ring-1 focus:ring-[#7c4959] text-sm">
                            <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Kategori</label>
                            <input type="text" name="category" value="{{ request('category') }}" placeholder="Buket..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#7c4959] text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Kabupaten</label>
                            <input type="text" name="regency" value="{{ request('regency') }}" placeholder="Kota..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#7c4959] text-sm">
                        </div>
                    </div>
                    <div class="flex gap-3 items-end">
                        <div class="flex-grow">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Kecamatan</label>
                            <input type="text" name="district" value="{{ request('district') }}" placeholder="Kecamatan..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-[#7c4959] text-sm">
                        </div>
                        <button type="submit" data-a11y="btn-search-mobile" class="px-4 py-2 bg-[#7c4959] text-white rounded-lg font-bold text-sm h-[38px]">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DESKTOP: Standard Filter (unchanged) --}}
        <div class="hidden md:block bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8" data-a11y="filter-area">
            <form method="GET" action="{{ route('katalog.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="text-a11y-admin block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nama Bunga</label>
                    <div class="relative">
                        <input type="text" name="search" data-a11y="input-search" value="{{ request('search') }}" placeholder="Contoh: Mawar..." class="input-a11y-admin w-full pl-10 pr-4 py-2.5 rounded-md border border-gray-300 focus:outline-none focus:border-[#7c4959] focus:ring-1 focus:ring-[#7c4959] text-sm">
                        <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <label class="text-a11y-admin block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Kategori</label>
                    <div class="relative">
                        <input type="text" name="category" data-a11y="input-category" value="{{ request('category') }}" placeholder="Contoh: Buket..." class="input-a11y-admin w-full pl-10 pr-4 py-2.5 rounded-md border border-gray-300 focus:outline-none focus:border-[#7c4959] focus:ring-1 focus:ring-[#7c4959] text-sm">
                        <i class="fa-solid fa-layer-group absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <label class="text-a11y-admin block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Kabupaten/Kota</label>
                    <div class="relative">
                        <input type="text" name="regency" data-a11y="input-location" value="{{ request('regency') }}" placeholder="Contoh: Banjarnegara..." class="input-a11y-admin w-full pl-10 pr-4 py-2.5 rounded-md border border-gray-300 focus:outline-none focus:border-[#7c4959] focus:ring-1 focus:ring-[#7c4959] text-sm">
                        <i class="fa-solid fa-city absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex gap-2 items-end">
                    <div class="flex-grow">
                        <label class="text-a11y-admin block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Kecamatan</label>
                        <div class="relative">
                            <input type="text" name="district" value="{{ request('district') }}" placeholder="Contoh: Bawang..." class="input-a11y-admin w-full pl-10 pr-4 py-2.5 rounded-md border border-gray-300 focus:outline-none focus:border-[#7c4959] focus:ring-1 focus:ring-[#7c4959] text-sm">
                            <i class="fa-solid fa-map-location-dot absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <button type="submit" data-a11y="btn-search" class="btn-a11y-admin inline-flex justify-center items-center px-4 py-2.5 bg-[#7c4959] text-white rounded-md font-bold text-sm uppercase hover:bg-[#5d3642] transition-colors h-[42px]">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>

        @if(isset($isSearch) && $isSearch)
            <div class="mb-6 flex justify-between items-center border-b border-[#d4ccc0]/50 pb-4">
                <h2 class="text-lg md:text-xl font-bold text-[#7c4959]">Hasil Pencarian Bunga</h2>
                <a href="{{ route('katalog.index') }}" class="text-sm font-semibold text-gray-500 hover:text-[#7c4959]">Reset Filter &times;</a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow flex flex-col group relative">
                        @if(!Auth::check() || Auth::user()->role === 'user')
                            @if(Auth::check())
                                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-2 right-2 md:top-3 md:right-3 z-10">
                                    @csrf
                                    <button type="submit" class="w-7 h-7 md:w-8 md:h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm transition-transform hover:scale-110 {{ Auth::user()->favoriteProducts->contains($product->id) ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }}">
                                        <i class="fa-solid fa-heart text-xs md:text-sm"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="absolute top-2 right-2 md:top-3 md:right-3 z-10 w-7 h-7 md:w-8 md:h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-red-400 shadow-sm transition-transform hover:scale-110" title="Login untuk menyimpan favorit">
                                    <i class="fa-solid fa-heart text-xs md:text-sm"></i>
                                </a>
                            @endif
                        @endif

                        <a href="{{ route('product.show', ['slug' => $product->slug, 'ref' => 'katalog']) }}" class="block aspect-square overflow-hidden bg-gray-100">
                            @if($product->image_path)
                                <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('images/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200"><i class="fa-solid fa-image text-3xl text-gray-400"></i></div>
                            @endif
                        </a>
                        <div class="p-3 md:p-4 flex flex-col flex-grow">
                            <div class="text-[9px] md:text-[10px] font-bold text-[#7c4959] uppercase tracking-wider mb-1">{{ $product->category->name ?? '' }}</div>
                            <a href="{{ route('product.show', ['slug' => $product->slug, 'ref' => 'katalog']) }}" class="font-bold text-gray-900 leading-snug hover:text-[#7c4959] mb-2 text-sm md:text-base line-clamp-2">{{ $product->name }}</a>
                            
                            <div class="hidden md:flex items-center text-xs text-gray-500 gap-1.5 mb-4 border-t pt-3">
                                 <i class="fa-solid fa-store text-[#926a7a]"></i> 
                                 <a href="{{ route('shop.show', $product->shop->id) }}" class="hover:text-[#7c4959]">{{ $product->shop->name }}</a>
                                 <span class="text-gray-300">•</span>
                                 <span>{{ $product->shop->district->name ?? '' }}</span>
                            </div>

                            <div class="mt-auto">
                                <div class="text-sm md:text-lg font-extrabold text-gray-900 mb-2 md:mb-4">Rp {{ number_format($product->price,0,',','.') }}</div>
                                <a href="{{ route('product.whatsapp', $product->id) }}" target="_blank" data-a11y="btn-order" class="btn-a11y-pesan block w-full py-2 md:py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-center font-bold text-xs md:text-sm rounded-lg border border-emerald-800 shadow-sm transition-colors">
                                   <i class="fa-brands fa-whatsapp mr-1"></i> Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-xl border border-gray-200">
                        Tidak ada bunga yang ditemukan sesuai kriteria pencarian Anda.
                    </div>
                @endforelse
            </div>
        @else
            <div class="space-y-8 md:space-y-12">
                @foreach($groupedProducts as $category)
                    @if($category->products->isNotEmpty())
                        <section>
                            <div class="flex justify-between items-end mb-4 md:mb-6 border-b border-[#d4ccc0]/50 pb-2">
                                <div>
                                    <h3 class="text-lg md:text-2xl font-bold text-gray-800 mb-0.5 md:mb-1">{{ $category->name }}</h3>
                                    <p class="text-xs md:text-sm text-gray-500">{{ $category->description ?? 'Rekomendasi kusuma terbaik' }}</p>
                                </div>
                                <a href="{{ route('katalog.index', ['category' => $category->name]) }}" class="text-xs md:text-sm font-semibold text-[#7c4959] hover:text-[#5d3642] flex items-center gap-1 transition-colors whitespace-nowrap">
                                    Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6">
                                @foreach($category->products as $product)
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow flex flex-col group relative">
                                        @if(!Auth::check() || Auth::user()->role === 'user')
                                            @if(Auth::check())
                                                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-2 right-2 md:top-3 md:right-3 z-10">
                                                    @csrf
                                                    <button type="submit" class="w-7 h-7 md:w-8 md:h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm transition-transform hover:scale-110 {{ Auth::user()->favoriteProducts->contains($product->id) ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }}">
                                                        <i class="fa-solid fa-heart text-xs md:text-sm"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('login') }}" class="absolute top-2 right-2 md:top-3 md:right-3 z-10 w-7 h-7 md:w-8 md:h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-red-400 shadow-sm transition-transform hover:scale-110" title="Login untuk menyimpan favorit">
                                                    <i class="fa-solid fa-heart text-xs md:text-sm"></i>
                                                </a>
                                            @endif
                                        @endif

                                        <a href="{{ route('product.show', ['slug' => $product->slug, 'ref' => 'katalog']) }}" class="block aspect-square overflow-hidden bg-gray-100">
                                            @if($product->image_path)
                                                <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('images/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200"><i class="fa-solid fa-image text-3xl text-gray-400"></i></div>
                                            @endif
                                        </a>
                                        <div class="p-3 md:p-4 flex flex-col flex-grow">
                                            <div class="text-[9px] md:text-[10px] font-bold text-[#7c4959] uppercase tracking-wider mb-1">{{ $product->category->name ?? '' }}</div>
                                            <a href="{{ route('product.show', ['slug' => $product->slug, 'ref' => 'katalog']) }}" class="font-bold text-gray-900 leading-snug hover:text-[#7c4959] mb-2 text-sm md:text-base line-clamp-2">{{ $product->name }}</a>
                                            
                                            <div class="hidden md:flex items-center text-xs text-gray-500 gap-1.5 mb-4 border-t pt-3">
                                                 <i class="fa-solid fa-store text-[#926a7a]"></i> 
                                                 <a href="{{ route('shop.show', $product->shop->id) }}" class="hover:text-[#7c4959]">{{ $product->shop->name }}</a>
                                                 <span class="text-gray-300">•</span>
                                                 <span>{{ $product->shop->district->name ?? '' }}</span>
                                            </div>

                                            <div class="mt-auto">
                                                <div class="text-sm md:text-lg font-extrabold text-gray-900 mb-2 md:mb-4">Rp {{ number_format($product->price,0,',','.') }}</div>
                                                <a href="{{ route('product.whatsapp', $product->id) }}" target="_blank" data-a11y="btn-order" class="btn-a11y-pesan block w-full py-2 md:py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-center font-bold text-xs md:text-sm rounded-lg border border-emerald-800 shadow-sm transition-colors">
                                                   <i class="fa-brands fa-whatsapp mr-1"></i> Pesan Sekarang
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach
            </div>
        @endif
    </main>

    <footer class="bg-[#ac9a9c]/10 pt-12 pb-8 border-t border-[#d4ccc0] mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <span class="text-2xl font-extrabold text-plum">Flora<span class="text-[#926a7a]">Mart</span></span>
                    <p class="mt-4 text-sm text-gray-600">Menghantarkan semerbak kasih dan kehangatan ke setiap sudut ruang. Lebih dari sekadar niaga, ini adalah simfoni keindahan dari Banjarnegara.</p>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-plum uppercase tracking-wider">Layanan</h3>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li>Garansi Kesegaran 100%</li>
                        <li>Pengiriman Instan Florist</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-plum uppercase tracking-wider">Kontak</h3>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li>Jl. Klampok Gombong, Banjarnegara</li>
                        <li>WA: 0895-3012-3608</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-[#d4ccc0]/50 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} FloraMart Indonesia. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>

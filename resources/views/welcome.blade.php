<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FloraMart - Marketplace Toko Bunga Lokal</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

   <nav class="bg-white border-b border-[#d4ccc0]/50 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold text-[#7c4959] tracking-tight">Flora<span class="text-[#926a7a]">Mart</span></a>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-[#7c4959] transition-colors">Dasbor Anda</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-[#7c4959] transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-[#7c4959] text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-[#5d3642] shadow-sm transition-all">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-gradient-to-b from-white to-[#d4ccc0]/20 border-b border-[#d4ccc0]/50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl lg:text-6xl">
                Cari Bunga Segar dari Toko <span class="text-[#7c4959]">Terdekat Anda</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-base text-gray-600 sm:text-lg">
                Menghubungkan Anda langsung dengan UMKM Florist lokal di Nusantara. Pengiriman instan, kondisi segar, dan transaksi langsung melalui WhatsApp.
            </p>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="text-center mb-6">
            <h2 class="text-sm font-bold text-[#926a7a] tracking-widest uppercase">Eksplorasi Kategori</h2>
        </div>
        <div class="flex flex-wrap justify-center gap-6 md:gap-10">
            @foreach($categories as $cat)
                <a href="{{ route('home', ['category' => $cat->id]) }}" class="group flex flex-col items-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-[#d4ccc0]/30 border border-[#ac9a9c]/50 flex items-center justify-center group-hover:bg-[#7c4959] transition-colors duration-300 shadow-sm">
                        <span class="text-xl md:text-2xl font-bold text-[#7c4959] group-hover:text-white transition-colors">
                            {{ substr($cat->name, 0, 1) }}
                        </span>
                    </div>
                    <span class="mt-3 text-xs md:text-sm font-semibold text-gray-700 group-hover:text-[#7c4959] transition-colors">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-md border border-[#d4ccc0]/50">
            <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                <div>
                    <label for="category" class="block text-xs font-bold uppercase tracking-wider text-[#926a7a] mb-2">Pilih Jenis Bunga</label>
                    <select name="category" id="category" class="block w-full border-gray-200 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md shadow-sm text-sm transition">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="district" class="block text-xs font-bold uppercase tracking-wider text-[#926a7a] mb-2">Lokasi Wilayah (Kecamatan)</label>
                    <select name="district" id="district" class="block w-full border-gray-200 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md shadow-sm text-sm transition">
                        <option value="">Semua Lokasi</option>
                        @foreach($districts as $dst)
                            <option value="{{ $dst->id }}" {{ request('district') == $dst->id ? 'selected' : '' }}>
                                {{ $dst->name }} ({{ $dst->regency->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-[#7c4959] border border-transparent rounded-md font-bold text-sm text-white uppercase tracking-wider hover:bg-[#5d3642] shadow-sm transition-colors">
                        Cari Bunga Terdekat
                    </button>
                </div>
            </form>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-16">

        @if($isSearch)
            <div class="mb-6 flex justify-between items-center border-b border-[#d4ccc0]/50 pb-4">
                <h2 class="text-xl font-bold text-[#7c4959]">Hasil Pencarian Bunga</h2>
                <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-500 hover:text-[#7c4959]">Reset Pencarian &times;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-xl border border-[#d4ccc0]/40 overflow-hidden flex flex-col group hover:shadow-md transition-all duration-300 relative">

                        @if(Auth::check())
                            @php
                                $isFav = Auth::user()->favoriteProducts()->where('product_id', $product->id)->exists();
                            @endphp
                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                @csrf
                                <button type="submit" class="p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-sm hover:scale-110 transition-transform">
                                    @if($isFav)
                                        <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="absolute top-3 right-3 z-10 p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-sm hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </a>
                        @endif

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
                                    <span class="mx-1.5 text-gray-300">•</span>
                                    <span class="text-gray-600">{{ $product->shop->district->name }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-base font-bold text-gray-900 mb-2">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                                <a href="{{ route('product.show', $product->slug) }}" class="block w-full border border-gray-200 text-gray-700 hover:border-[#7c4959] hover:text-[#7c4959] rounded-lg text-xs font-semibold py-2 transition-all text-center mt-2">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-white rounded-xl border border-[#d4ccc0]/50 p-8">
                        <p class="text-gray-500 text-lg font-medium">Maaf, bunga yang Anda cari belum tersedia di area tersebut.</p>
                    </div>
                @endforelse
            </div>

        @else
            <div class="space-y-16">
                @foreach($groupedProducts as $category)
                    @if($category->products->count() > 0)
                        <section>
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $category->name }} Pilihan</h2>
                                <a href="{{ route('home', ['category' => $category->id]) }}" class="text-sm font-semibold text-[#7c4959] hover:text-[#5d3642] flex items-center transition-colors">
                                    Lihat Semua
                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach($category->products as $product)
                                    <div class="bg-white rounded-xl border border-[#d4ccc0]/40 overflow-hidden flex flex-col group hover:shadow-md transition-all duration-300 relative">

                                        @if(Auth::check())
                                            @php
                                                $isFav = Auth::user()->favoriteProducts()->where('product_id', $product->id)->exists();
                                            @endphp
                                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                                @csrf
                                                <button type="submit" class="p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-sm hover:scale-110 transition-transform">
                                                    @if($isFav)
                                                        <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                                    @endif
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="absolute top-3 right-3 z-10 p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-sm hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            </a>
                                        @endif

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
                                                    <span class="mx-1.5 text-gray-300">•</span>
                                                    <span class="text-gray-600">{{ $product->shop->district->name }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-base font-bold text-gray-900 mb-2">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </div>
                                                <a href="{{ route('product.show', $product->slug) }}" class="block w-full border border-gray-200 text-gray-700 hover:border-[#7c4959] hover:text-[#7c4959] rounded-lg text-xs font-semibold py-2 transition-all text-center mt-2">
                                                    Lihat Detail
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

    <footer class="bg-[#ac9a9c]/10 pt-16 pb-8 border-t border-[#d4ccc0] mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8">

                <div class="md:col-span-12 lg:col-span-5">
                    <span class="text-3xl font-extrabold text-[#7c4959] tracking-tight">Flora<span class="text-[#926a7a]">Mart</span></span>
                    <p class="mt-6 text-sm leading-relaxed text-gray-600 italic">
                        "Di bawah naungan semesta, FloraMart hadir merajut <span class="font-semibold text-[#7c4959]">kusuma</span> menjadi mahakarya. Kami menyatukan <span class="font-semibold text-[#7c4959]">karsa</span> para perajin lokal, menghantarkan semerbak kasih dan kehangatan ke setiap sudut ruang. Lebih dari sekadar niaga, ini adalah simfoni keindahan dari Banjarnegara untuk Nusantara."
                    </p>

                    <div class="mt-6 flex space-x-4">
                        <a href="https://www.instagram.com/mualifakhyar_/" target="_blank" class="text-[#926a7a] hover:text-[#7c4959] transition-colors">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="md:col-span-4 lg:col-span-3">
                    <h3 class="text-sm font-bold text-[#7c4959] tracking-wider uppercase">Layanan Pelanggan</h3>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-[#926a7a] mr-2"></span> Garansi Kesegaran 100%</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-[#926a7a] mr-2"></span> Panduan Transaksi Aman</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-[#926a7a] mr-2"></span> Pusat Bantuan (FAQ)</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-[#926a7a] mr-2"></span> Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-[#926a7a] mr-2"></span> Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div class="md:col-span-8 lg:col-span-4">
                    <h3 class="text-sm font-bold text-[#7c4959] tracking-wider uppercase">Kantor Operasional</h3>
                    <ul class="mt-4 space-y-4 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-[#926a7a] mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="leading-relaxed">
                                Jl. Klampok Gombong, Desa Blimbing,<br>
                                Kecamatan Mandiraja,<br>
                                Kabupaten Banjarnegara, Jawa Tengah.
                            </span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-[#926a7a] mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="font-medium">0895-3012-3608</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-[#926a7a] mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>akhyarmualif422006@gmail.com</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="mt-12 pt-8 border-t border-[#d4ccc0]/50 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} FloraMart Banjarnegara. Hak Cipta Dilindungi.
                </p>
                <div class="mt-4 md:mt-0 flex space-x-4">
                    <span class="text-xs text-gray-400 font-medium tracking-widest uppercase">Connecting Local Florists</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

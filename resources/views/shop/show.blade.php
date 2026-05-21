<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $shop->name }} - FloraMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans flex flex-col min-h-screen">

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

    <main class="flex-grow pb-16">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-[#7c4959] transition-colors mb-2 group">
                <svg class="h-4 w-4 mr-1.5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda Utama
            </a>
        </div>

        <div class="bg-gradient-to-b from-[#7c4959]/10 to-white border-b border-[#d4ccc0]/50 pt-6 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-sm border border-[#d4ccc0]/50 p-8 flex flex-col md:flex-row items-center md:items-start gap-6 relative overflow-hidden">

                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-[#d4ccc0]/20 rounded-full blur-3xl"></div>

                    <div class="w-24 h-24 rounded-full bg-[#7c4959] text-white flex items-center justify-center text-3xl font-extrabold shadow-md flex-shrink-0 z-10">
                        {{ strtoupper(substr($shop->name, 0, 1)) }}
                    </div>

                    <div class="text-center md:text-left z-10 flex-1">
                        <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $shop->name }}</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Terverifikasi
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm text-gray-600 mb-4">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-[#926a7a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $shop->district->name }}, {{ $shop->district->regency->name }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-[#926a7a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Pemilik: {{ $shop->user->name }}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm leading-relaxed max-w-3xl">
                            {{ $shop->description ?? 'Toko bunga terpercaya yang menyediakan berbagai macam buket dan karangan bunga segar.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
            <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-[#d4ccc0]/50 pb-4">Etalase Bunga ({{ $products->count() }})</h2>

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
                                <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 group-hover:text-[#7c4959] transition-colors mb-2">{{ $product->name }}</h3>
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
                        <p class="text-gray-500 text-lg font-medium">Toko ini belum memiliki etalase bunga yang aktif.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <footer class="bg-[#ac9a9c]/10 pt-16 pb-8 border-t border-[#d4ccc0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8">
                <div class="md:col-span-12 lg:col-span-5">
                    <span class="text-3xl font-extrabold text-[#7c4959] tracking-tight">Flora<span class="text-[#926a7a]">Mart</span></span>
                    <p class="mt-6 text-sm leading-relaxed text-gray-600 italic">
                        "Di bawah naungan semesta, FloraMart hadir merajut <span class="font-semibold text-[#7c4959]">kusuma</span> menjadi mahakarya. Kami menyatukan <span class="font-semibold text-[#7c4959]">karsa</span> para perajin lokal, menghantarkan semerbak kasih dan kehangatan ke setiap sudut ruang. Lebih dari sekadar niaga, ini adalah simfoni keindahan dari Banjarnegara untuk Nusantara."
                    </p>
                </div>
                <div class="md:col-span-4 lg:col-span-3">
                    <h3 class="text-sm font-bold text-[#7c4959] tracking-wider uppercase">Layanan Pelanggan</h3>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors">Garansi Kesegaran 100%</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors">Pusat Bantuan (FAQ)</a></li>
                    </ul>
                </div>
                <div class="md:col-span-8 lg:col-span-4">
                    <h3 class="text-sm font-bold text-[#7c4959] tracking-wider uppercase">Kantor Operasional</h3>
                    <ul class="mt-4 space-y-4 text-sm text-gray-600">
                        <li class="flex items-start">
                            <span class="leading-relaxed">Jl. Klampok Gombong, Desa Blimbing,<br>Kecamatan Mandiraja,<br>Kabupaten Banjarnegara, Jawa Tengah.</span>
                        </li>
                        <li class="font-medium">WA: 0895-3012-3608</li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-[#d4ccc0]/50 flex justify-between items-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} FloraMart Banjarnegara. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

</body>
</html>

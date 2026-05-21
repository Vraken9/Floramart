<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - FloraMart</title>
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

    @php
        // Logika Wishlist
        $isFavorited = false;
        if(Auth::check()) {
            $isFavorited = Auth::user()->favoriteProducts()->where('product_id', $product->id)->exists();
        }

        // Logika WhatsApp
        $waNumber = $product->shop->whatsapp_number;
        if(str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }
        $hargaFormat = number_format($product->price, 0, ',', '.');
        $pesanWa = urlencode("Halo {$product->shop->name}, saya tertarik untuk memesan bunga *{$product->name}* seharga Rp {$hargaFormat} yang saya lihat di FloraMart. Apakah masih bisa dipesan?");
        $waLink = "https://wa.me/{$waNumber}?text={$pesanWa}";
    @endphp

    <main class="flex-grow py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-[#7c4959] transition">Beranda</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('home', ['category' => $product->category_id]) }}" class="hover:text-[#7c4959] transition">{{ $product->category->name }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-gray-400 font-medium line-clamp-1">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-[#d4ccc0]/50 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

                    <div class="bg-[#d4ccc0]/10 p-8 md:p-12 flex items-center justify-center relative group">
                        @if(str_starts_with($product->image_path, 'http'))
                            <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[500px] object-contain rounded-xl shadow-sm group-hover:scale-105 transition-transform duration-500">
                        @else
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[500px] object-contain rounded-xl shadow-sm group-hover:scale-105 transition-transform duration-500">
                        @endif
                        <span class="absolute top-6 left-6 px-3 py-1 text-xs font-bold tracking-wider uppercase bg-white/90 backdrop-blur-sm text-[#7c4959] rounded shadow-sm border border-[#d4ccc0]/30">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <div class="p-8 md:p-12 flex flex-col justify-center">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">{{ $product->name }}</h1>

                        <div class="flex items-center text-sm text-gray-500 mb-6 pb-6 border-b border-gray-100">
                            <span class="font-medium text-[#7c4959] bg-[#7c4959]/10 px-2.5 py-0.5 rounded-full mr-3">Toko Terverifikasi</span>
                            <span class="flex items-center hover:text-[#7c4959] transition cursor-pointer">
                                <svg class="h-4 w-4 mr-1 text-[#926a7a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $product->shop->name }} ({{ $product->shop->district->name }})
                            </span>
                        </div>

                        <div class="mb-8">
                            <p class="text-sm text-gray-500 mb-1 uppercase tracking-wider font-semibold">Harga Spesial</p>
                            <p class="text-4xl font-extrabold text-gray-900">Rp {{ $hargaFormat }}</p>
                        </div>

                        <div class="mb-10">
                            <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Detail Produk</h3>
                            <p class="text-gray-600 leading-relaxed text-base">{{ $product->description }}</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                            <a href="{{ $waLink }}" target="_blank" class="flex-1 inline-flex justify-center items-center px-8 py-4 bg-[#7c4959] border border-transparent rounded-xl font-bold text-white uppercase tracking-wider hover:bg-[#5d3642] shadow-md transition-all">
                                Pesan via WhatsApp
                            </a>

                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="sm:w-auto">
                                @csrf
                                <button type="submit" class="w-full h-full sm:w-16 inline-flex justify-center items-center px-4 py-4 bg-white border-2 border-[#d4ccc0] text-[#7c4959] rounded-xl hover:bg-[#d4ccc0]/10 transition-all">
                                    @if($isFavorited)
                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
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

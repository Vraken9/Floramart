<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Toko Florist - FloraMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans flex flex-col min-h-screen">
    <x-navigation />

    <main class="flex-grow pb-16 pt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <h1 class="text-a11y-admin text-4xl font-extrabold text-[#7c4959] tracking-tight mb-4">Direktori Toko Florist</h1>
                <p class="text-a11y-admin text-gray-600 max-w-2xl mx-auto">Jelajahi perajin lokal terbaik di seluruh Banjarnegara. Pilih lokasi Anda dan temukan karya seni merajut bunga langsung dari sumbernya.</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
                <form method="GET" action="{{ route('shops.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="text-a11y-admin block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nama Toko</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari toko..." class="input-a11y-admin w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-md text-sm focus:border-[#7c4959] focus:ring-1 focus:ring-[#7c4959] focus:outline-none">
                            <i class="fa-solid fa-store absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="text-a11y-admin block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Kabupaten/Kota</label>
                        <div class="relative">
                            <input type="text" name="regency" value="{{ request('regency') }}" placeholder="Contoh: Banjarnegara..." class="input-a11y-admin w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-md text-sm focus:border-[#7c4959] focus:ring-1 focus:ring-[#7c4959] focus:outline-none">
                            <i class="fa-solid fa-city absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="text-a11y-admin block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Kecamatan</label>
                        <div class="relative">
                            <input type="text" name="district" value="{{ request('district') }}" placeholder="Contoh: Bawang..." class="input-a11y-admin w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-md text-sm focus:border-[#7c4959] focus:ring-1 focus:ring-[#7c4959] focus:outline-none">
                            <i class="fa-solid fa-map-location-dot absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-a11y-admin w-full bg-[#7c4959] text-white py-2.5 rounded-md font-bold text-sm hover:bg-[#5d3642] h-[42px] flex items-center justify-center gap-2">
                        <i class="fa-solid fa-search"></i> Cari
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($shops as $shop)
                    <div class="bg-white rounded-2xl border border-[#d4ccc0]/40 overflow-hidden group hover:shadow-md transition-all duration-300 p-6 flex flex-col items-center text-center">
                        <a href="{{ route('shop.show', $shop->id) }}" class="inline-block relative mb-4">
                            @if($shop->logo_path)
                                <img src="{{ str_starts_with($shop->logo_path, 'http') ? $shop->logo_path : asset('images/' . $shop->logo_path) }}" alt="{{ $shop->name }}" class="w-24 h-24 rounded-full object-cover shadow-sm group-hover:scale-105 transition-transform duration-300 border-2 border-[#d4ccc0]">
                            @else
                                <div class="w-24 h-24 rounded-full bg-[#7c4959] text-white flex items-center justify-center text-3xl font-extrabold shadow-sm group-hover:scale-105 transition-transform duration-300">
                                    {{ strtoupper(substr($shop->name, 0, 1)) }}
                                </div>
                            @endif
                        </a>
                        <a href="{{ route('shop.show', $shop->id) }}" class="text-xl font-bold text-gray-900 hover:text-[#7c4959] transition-colors mb-2">
                            {{ $shop->name }}
                        </a>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#d4ccc0]/20 text-[#7c4959] mb-2">
                            <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $shop->district->name }}, {{ $shop->district->regency->name }}
                        </span>
                        @if($shop->is_branch && $shop->parentShop)
                            <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-purple-100 text-purple-700 mb-4 border border-purple-200">
                                <i class="fa-solid fa-code-branch mr-1"></i> Cabang: {{ $shop->parentShop->name }}
                            </span>
                        @else
                            <div class="mb-4"></div>
                        @endif
                        <p class="text-sm text-gray-500 leading-relaxed mb-6 line-clamp-3">
                            {{ $shop->description ?? 'Toko bunga terpercaya yang menyediakan berbagai macam buket dan karangan bunga segar dari Banjarnegara.' }}
                        </p>
                        <a href="{{ route('shop.show', $shop->id) }}" class="btn-a11y-admin mt-auto inline-flex justify-center items-center px-4 py-2 border border-[#d4ccc0]/50 text-gray-700 hover:border-[#7c4959] hover:text-[#7c4959] hover:bg-[#7c4959]/5 rounded-lg text-xs font-bold tracking-wider uppercase transition-all w-full">
                            Kunjungi Toko
                        </a>
                    </div>
                @empty
                    <div class="col-span-full bg-[#d4ccc0]/10 rounded-2xl p-16 text-center border border-[#d4ccc0]/30 shadow-sm">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white mb-4 shadow-sm border border-[#d4ccc0]/50">
                            <svg class="w-8 h-8 text-[#926a7a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2 tracking-tight">Belum ada Toko Florist ditemukan</h3>
                        <p class="text-gray-500 text-sm max-w-md mx-auto">Kami belum menemukan toko bunga yang sesuai dengan kriteria yang Anda cari di wilayah tersebut.</p>
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
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center">Garansi Kesegaran 100%</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center">Panduan Transaksi Aman</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center">Pusat Bantuan (FAQ)</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors flex items-center">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="md:col-span-8 lg:col-span-4">
                    <h3 class="text-sm font-bold text-[#7c4959] tracking-wider uppercase">Kantor Operasional</h3>
                    <ul class="mt-4 space-y-4 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-[#926a7a] mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="leading-relaxed">Jl. Klampok Gombong, Desa Blimbing,<br>Kecamatan Mandiraja,<br>Kabupaten Banjarnegara, Jawa Tengah.</span>
                        </li>
                        <li class="flex items-center font-medium">
                            <span class="w-5 h-5 flex items-center justify-center mr-3"><span class="w-1.5 h-1.5 bg-[#926a7a] rounded-full"></span></span>
                            WA: 0895-3012-3608
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-[#d4ccc0]/50 flex justify-between items-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} FloraMart. Mahakarya Banjarnegara.</p>
            </div>
        </div>
    </footer>
</body>
</html>
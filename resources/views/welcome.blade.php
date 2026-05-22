<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FloraMart - Pesan Bunga Segar Langsung dari Florist</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-plum { background-color: #7c4959; }
        .text-plum { color: #7c4959; }
        .border-plum { border-color: #7c4959; }
        .hover-bg-plum-dark:hover { background-color: #5d3642; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased flex flex-col min-h-screen">

    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold text-plum flex items-center gap-2">
                        Flora<span class="text-[#926a7a]">Mart</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-900 border-b-2 border-plum font-semibold">Beranda</a>
                    <a href="{{ route('katalog.index') }}" class="text-gray-500 hover:text-plum font-semibold">Katalog Bunga</a>
                    <a href="{{ route('shops.index') }}" class="text-gray-500 hover:text-plum font-semibold">Toko Florist</a>
                    @if (Auth::check())
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-plum font-semibold border-l pl-4 border-gray-300">Dasbor</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-plum font-semibold border-l pl-4 border-gray-300">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-plum hover-bg-plum-dark text-white px-4 py-2 rounded-md text-xs font-semibold uppercase shadow-sm">Daftar</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        <section class="relative bg-gray-50 py-20 overflow-hidden">
            <div class="absolute inset-0 bg-[#d4ccc0] opacity-10"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2 text-center md:text-left">
                    <span class="inline-block py-1 px-3 rounded-full bg-plum/10 text-plum text-xs font-bold tracking-widest uppercase mb-6 border border-plum/20">Pusat Florist Banjarnegara</span>
                    <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6">
                        Sampaikan Pesan Cinta dengan <span class="text-plum">Kusuma Sempurna</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-lg mx-auto md:mx-0">
                        Platform resmi yang menghubungkan Anda langsung dengan pengrajin karangan bunga dan florist lokal terpercaya. Transaksi mudah, pengiriman instan.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="{{ route('katalog.index') }}" class="px-8 py-4 bg-plum text-white rounded-lg font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all">Mulai Belanja</a>
                        <a href="{{ route('shops.index') }}" class="px-8 py-4 bg-white text-plum border border-gray-200 rounded-lg font-bold hover:bg-gray-50 transition-colors">Lihat Mitra Florist</a>
                    </div>
                </div>
                <div class="md:w-1/2 hidden md:block">
                    <div class="aspect-[4/3] bg-gray-200 rounded-2xl shadow-2xl overflow-hidden border-8 border-white">
                        <div class="w-full h-full bg-plum/20 flex items-center justify-center">
                            <i class="fa-solid fa-seedling text-8xl text-white opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="p-6">
                        <div class="w-16 h-16 mx-auto bg-[#d4ccc0]/30 rounded-full flex items-center justify-center text-plum text-2xl mb-4"><i class="fa-solid fa-leaf"></i></div>
                        <h3 class="text-xl font-bold mb-2">100% Segar & Asli</h3>
                        <p class="text-gray-500 text-sm">Bunga dirangkai langsung di hari pengiriman oleh florist profesional untuk menjamin kesegaran maksimal.</p>
                    </div>
                    <div class="p-6">
                        <div class="w-16 h-16 mx-auto bg-[#d4ccc0]/30 rounded-full flex items-center justify-center text-plum text-2xl mb-4"><i class="fa-solid fa-handshake"></i></div>
                        <h3 class="text-xl font-bold mb-2">Tanpa Perantara</h3>
                        <p class="text-gray-500 text-sm">Anda berkomunikasi dan bertransaksi langsung dengan pemilik toko via WhatsApp. Harga jujur dan transparan.</p>
                    </div>
                    <div class="p-6">
                        <div class="w-16 h-16 mx-auto bg-[#d4ccc0]/30 rounded-full flex items-center justify-center text-plum text-2xl mb-4"><i class="fa-solid fa-truck-fast"></i></div>
                        <h3 class="text-xl font-bold mb-2">Jangkauan Luas</h3>
                        <p class="text-gray-500 text-sm">Sistem filter berbasis lokasi kami memastikan bunga pesanan Anda tiba tepat waktu, di mana pun Anda berada.</p>
                    </div>
                </div>
            </div>
        </section>
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
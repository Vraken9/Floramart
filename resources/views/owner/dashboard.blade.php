<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Owner Florist Dashboard - FloraMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans flex flex-col min-h-screen">
    <x-navigation />

    <main class="flex-grow py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="font-bold text-2xl text-[#7c4959] leading-tight flex items-center">
                    <i class="fa-solid fa-store mr-3"></i> {{ __('Owner Florist Dashboard') }}
                </h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-[#d4ccc0]/50">
                <div class="p-8 text-gray-900">
                    <h3 class="text-xl font-bold mb-2 text-[#7c4959]">Selamat Datang di Dasbor Toko Anda!</h3>
                    <p class="text-gray-600 mb-8">Kelola katalog bunga Anda dan pantau performa toko di sini.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 lg:gap-6">
                        <a href="{{ route('owner.products.index') }}" class="block p-6 lg:p-8 bg-[#7c4959]/5 border border-[#7c4959]/20 rounded-xl hover:bg-[#7c4959]/10 hover:border-[#7c4959]/40 transition-all duration-300 group shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#7c4959] text-white rounded-lg flex items-center justify-center text-lg lg:text-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                </div>
                                <h4 class="text-[#7c4959] font-bold text-lg lg:text-xl ml-4">Katalog Bunga</h4>
                            </div>
                            <p class="text-gray-600 text-xs lg:text-sm mt-2">Kelola etalase, tambah produk baru, perbarui informasi, dan ubah harga dengan mudah.</p>
                        </a>

                        <div class="block p-6 lg:p-8 bg-gray-50 border border-gray-200 rounded-xl opacity-75 cursor-not-allowed transition-all duration-300 hover:shadow-sm">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gray-300 text-gray-600 rounded-lg flex items-center justify-center text-lg lg:text-xl">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <h4 class="text-gray-700 font-bold text-lg lg:text-xl ml-4">Analitik Klik WA</h4>
                            </div>
                            <p class="text-gray-500 text-xs lg:text-sm mt-2">Lihat statistik interaksi pengunjung dengan toko Anda. <span class="font-semibold text-[10px] lg:text-xs bg-gray-200 px-2 py-0.5 rounded ml-1">Segera Hadir</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

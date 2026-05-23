<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Pendaftaran Toko - FloraMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans flex flex-col min-h-screen">
    <x-navigation />

    <main class="flex-grow py-12 flex items-center justify-center">
        <div class="max-w-xl w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8 text-center relative">
                
                <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Toko Anda Sedang Diverifikasi</h2>
                <p class="text-gray-500 mb-8">Terima kasih telah mendaftar sebagai Owner di FloraMart. Saat ini, permohonan pendaftaran toko <strong class="text-gray-800">{{ $shop->name }}</strong> sedang dipertimbangkan oleh tim kami.</p>
                
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-5 text-left mb-8">
                    <h3 class="font-bold text-blue-900 mb-2 flex items-center"><i class="fa-solid fa-circle-info mr-2"></i> Informasi Status</h3>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex justify-between items-center py-1 border-b border-blue-100/50">
                            <span>Status Pendaftaran:</span>
                            <span class="font-bold uppercase tracking-wider bg-blue-200 text-blue-900 px-3 py-1 rounded-full text-xs">Sedang Dipertimbangkan</span>
                        </li>
                        <li class="flex justify-between items-center py-1 border-b border-blue-100/50">
                            <span>Tanggal Pengajuan:</span>
                            <span class="font-semibold">{{ $shop->created_at ? $shop->created_at->format('d M Y H:i') : '-' }}</span>
                        </li>
                        <li class="flex justify-between items-center py-1">
                            <span>Estimasi Verifikasi:</span>
                            <span class="font-semibold">1 - 2 Hari Kerja</span>
                        </li>
                    </ul>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <p class="text-sm font-semibold text-gray-800 mb-3">Punya pertanyaan atau kendala?</p>
                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20FloraMart,%20saya%20ingin%20bertanya%20tentang%20status%20pendaftaran%20toko%20saya%20({{ urlencode($shop->name) }})" target="_blank" class="inline-flex items-center justify-center px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg transition-colors text-sm shadow-sm">
                        <i class="fa-brands fa-whatsapp mr-2 text-lg"></i> Hubungi Admin di WhatsApp
                    </a>
                </div>

            </div>
            
            <div class="text-center mt-6">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800 font-semibold transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </main>
</body>
</html>

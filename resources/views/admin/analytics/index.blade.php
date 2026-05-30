<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analitik & Laporan - FloraMart Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">
    <x-navigation />

    <div class="flex flex-col lg:flex-row max-w-7xl mx-auto px-4 py-4 lg:py-8 gap-6">
        <x-admin-sidebar active="analitik" />

        <main class="flex-grow space-y-6 lg:space-y-8 min-w-0">
            <div class="border-b border-gray-200 pb-4">
                <h1 class="text-xl lg:text-2xl font-light text-gray-900 tracking-tight">Analitik <span class="font-bold text-[#7c4959]">Platform</span></h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                <!-- KPI 1 -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 lg:p-6 rounded-2xl border border-green-200 shadow-sm flex flex-col justify-center text-center transform transition duration-300 hover:scale-105 hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                        <i class="fa-brands fa-whatsapp text-lg lg:text-xl"></i>
                    </div>
                    <p class="text-xs lg:text-sm font-extrabold text-green-900 mb-1">Klik WhatsApp</p>
                    <p class="text-2xl lg:text-3xl font-black text-green-700">{{ number_format($totalWaClicks) }}</p>
                    <p class="text-[10px] lg:text-xs font-bold text-green-600 mt-2"><i class="fa-solid fa-arrow-trend-up"></i> +{{ number_format($recentWaClicks) }} (7 hr)</p>
                </div>

                <!-- KPI 2 -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 lg:p-6 rounded-2xl border border-blue-200 shadow-sm flex flex-col justify-center text-center transform transition duration-300 hover:scale-105 hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                        <i class="fa-solid fa-users text-lg lg:text-xl"></i>
                    </div>
                    <p class="text-xs lg:text-sm font-extrabold text-blue-900 mb-1">Pengunjung / User</p>
                    <p class="text-2xl lg:text-3xl font-black text-blue-700">{{ number_format($totalUsers) }}</p>
                    <p class="text-[10px] lg:text-xs font-bold text-blue-600 mt-2"><i class="fa-solid fa-user-plus"></i> +{{ number_format($recentRegistrations) }} (7 hr)</p>
                </div>

                <!-- KPI 3 -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 lg:p-6 rounded-2xl border border-purple-200 shadow-sm flex flex-col justify-center text-center transform transition duration-300 hover:scale-105 hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-purple-500 text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                        <i class="fa-solid fa-store text-lg lg:text-xl"></i>
                    </div>
                    <p class="text-xs lg:text-sm font-extrabold text-purple-900 mb-1">Total Etalase</p>
                    <p class="text-2xl lg:text-3xl font-black text-purple-700">{{ number_format($totalShops) }}</p>
                    <p class="text-[10px] lg:text-xs font-bold text-purple-600 mt-2">Toko Terdaftar</p>
                </div>

                <!-- KPI 4 -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 lg:p-6 rounded-2xl border border-orange-200 shadow-sm flex flex-col justify-center text-center transform transition duration-300 hover:scale-105 hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-orange-500 text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                        <i class="fa-solid fa-box text-lg lg:text-xl"></i>
                    </div>
                    <p class="text-xs lg:text-sm font-extrabold text-orange-900 mb-1">Produk Aktif</p>
                    <p class="text-2xl lg:text-3xl font-black text-orange-700">{{ number_format($totalProducts) }}</p>
                    <p class="text-[10px] lg:text-xs font-bold text-orange-600 mt-2">Bunga & Kerajinan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                <!-- Baris untuk chart masa depan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-6">
                    <h2 class="text-xs lg:text-sm font-semibold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-3">Interaksi Kunjungan Toko</h2>
                    <div class="h-40 lg:h-48 flex items-center justify-center bg-gray-50 rounded-lg border border-dashed border-gray-200">
                        <p class="text-[10px] lg:text-xs text-gray-400 text-center uppercase tracking-widest">Integrasi Tracker Belum Aktif</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-6">
                    <h2 class="text-xs lg:text-sm font-semibold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-3">Konversi Produk (Klik WA)</h2>
                    <div class="space-y-4">
                        @php
                            // Ambil 5 produk dengan klik terbanyak
                            $topProducts = \App\Models\ProductLead::select('product_id', \Illuminate\Support\Facades\DB::raw('count(*) as total_clicks'))
                                ->groupBy('product_id')
                                ->orderByDesc('total_clicks')
                                ->take(5)
                                ->get();
                        @endphp
                        @forelse($topProducts as $idx => $lead)
                            @php $prod = \App\Models\Product::find($lead->product_id); @endphp
                            @if($prod)
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-8 h-8 flex-shrink-0 bg-gray-50 text-[#7c4959] rounded flex items-center justify-center font-bold text-[10px] tracking-widest">#{{ $idx + 1 }}</div>
                                    <div class="min-w-0">
                                        <p class="text-xs lg:text-sm font-medium text-gray-900 truncate">{{ $prod->name }}</p>
                                        <p class="text-[9px] lg:text-[10px] text-gray-400 uppercase tracking-widest truncate">{{ $prod->shop->name ?? 'Toko Tidak Diketahui' }}</p>
                                    </div>
                                </div>
                                <div class="text-[10px] lg:text-xs flex-shrink-0 font-medium text-gray-600 bg-gray-50 px-2 lg:px-3 py-1 lg:py-1.5 rounded-md border border-gray-100 whitespace-nowrap">
                                    {{ $lead->total_clicks }} Klik
                                </div>
                            </div>
                            @endif
                        @empty
                            <p class="text-[10px] lg:text-xs text-gray-400 uppercase tracking-widest text-center py-8">Belum ada data konversi</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>
</html>

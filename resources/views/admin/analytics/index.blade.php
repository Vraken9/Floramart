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

    <div class="flex max-w-7xl mx-auto px-4 py-8 gap-6">
        <aside class="w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sticky top-24">
                <div class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-4 px-3">Menu Admin</div>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-chart-pie w-6"></i> Ringkasan
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-store w-6"></i> Kelola Toko
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-box w-6"></i> Kelola Produk
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-users w-6"></i> Kelola User
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-3 py-2.5 bg-red-50 text-red-700 rounded-lg font-bold">
                        <i class="fa-solid fa-chart-line w-6"></i> Analitik
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-grow space-y-6">
            <h1 class="text-2xl font-extrabold text-gray-900">Analitik Platform</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center"><i class="fa-brands fa-whatsapp"></i></div>
                        <p class="text-sm font-bold text-gray-600">Klik WhatsApp</p>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalWaClicks) }}</p>
                    <p class="text-xs text-gray-500 mt-2"><span class="text-green-500"><i class="fa-solid fa-arrow-up"></i> {{ number_format($recentWaClicks) }}</span> dlm 7 hari terakhir</p>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center"><i class="fa-solid fa-users"></i></div>
                        <p class="text-sm font-bold text-gray-600">Pengunjung / User</p>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalUsers) }}</p>
                    <p class="text-xs text-gray-500 mt-2"><span class="text-green-500"><i class="fa-solid fa-arrow-up"></i> {{ number_format($recentRegistrations) }}</span> user baru mendaftar (7h)</p>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center"><i class="fa-solid fa-store"></i></div>
                        <p class="text-sm font-bold text-gray-600">Total Etalase</p>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalShops) }}</p>
                    <p class="text-xs text-gray-500 mt-2">Toko terdaftar di sistem</p>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center"><i class="fa-solid fa-box"></i></div>
                        <p class="text-sm font-bold text-gray-600">Produk Tersedia</p>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalProducts) }}</p>
                    <p class="text-xs text-gray-500 mt-2">Bunga dan kerajinan aktif</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Baris untuk chart masa depan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="font-bold text-gray-800 mb-4 border-b pb-2">Interaksi Kunjungan Toko</h2>
                    <div class="h-48 flex items-center justify-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-sm text-gray-500 text-center"><i class="fa-solid fa-chart-bar block text-2xl mb-2 text-gray-300"></i> Integrasi Google Analytics / Tracker Kunjungan belum aktif.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="font-bold text-gray-800 mb-4 border-b pb-2">Konversi Produk (Klik WA)</h2>
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
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center font-bold text-gray-400 text-xs">#{{ $idx + 1 }}</div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $prod->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $prod->shop->name ?? 'Toko Tidak Diketahui' }}</p>
                                    </div>
                                </div>
                                <div class="text-sm font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">
                                    {{ $lead->total_clicks }} Klik
                                </div>
                            </div>
                            @endif
                        @empty
                            <p class="text-sm text-gray-500 italic text-center py-4">Belum ada data konversi WhatsApp.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>
</html>

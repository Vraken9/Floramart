<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin - FloraMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <x-navigation />

    <div class="flex max-w-7xl mx-auto px-4 py-8 gap-6">
        <aside class="w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sticky top-24">
                <div class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-4 px-3">Menu Admin</div>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 bg-red-50 text-red-700 rounded-lg font-bold">
                        <i class="fa-solid fa-chart-pie w-6"></i> Ringkasan
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-store w-6"></i> Kelola Toko
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-box w-6"></i> Kelola Produk
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-chart-line w-6"></i> Analitik
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-grow space-y-6">
            <h1 class="text-2xl font-extrabold text-gray-900">Dasbor Utama</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl"><i class="fa-solid fa-store"></i></div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Total Toko Aktif</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ \App\Models\Shop::where('status', 'approved')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl"><i class="fa-solid fa-box"></i></div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Total Produk</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ \App\Models\Product::count() }}</p>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xl"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Total Pengguna</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-bold text-gray-800">Menunggu Persetujuan (Pending)</h2>
                </div>
                <div class="p-6">
                    @if(isset($pendingShops) && $pendingShops->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($pendingShops as $shop)
                                <div class="py-4 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $shop->name }}</p>
                                        <p class="text-xs text-gray-500">Pemilik: {{ $shop->user->name }} | Daerah: {{ $shop->district->name }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.shops.approve', $shop->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-colors"><i class="fa-solid fa-check mr-1"></i> Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.shops.reject', $shop->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" onclick="return confirm('Tolak pendaftaran toko ini?')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-colors"><i class="fa-solid fa-xmark mr-1"></i> Tolak</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Tidak ada toko yang menunggu persetujuan.</p>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>

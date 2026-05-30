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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sticky top-24">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 px-3">Navigasi Utama</div>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 bg-gray-50 text-[#7c4959] rounded-lg font-medium text-sm">
                        Ringkasan
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Kelola Toko
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Kelola Produk
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Kelola Pengguna
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Analitik
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-grow space-y-8">
            <div class="border-b border-gray-200 pb-4">
                <h1 class="text-2xl font-light text-gray-900 tracking-tight">Ikhtisar <span class="font-bold text-[#7c4959]">Sistem</span></h1>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl border border-green-200 shadow-sm flex flex-col justify-center text-center transform transition duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                        <i class="fa-solid fa-store text-xl"></i>
                    </div>
                    <p class="text-sm font-extrabold text-green-900 mb-1">Toko Aktif</p>
                    <p class="text-3xl font-black text-green-700">{{ \App\Models\Shop::where('status', 'approved')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200 shadow-sm flex flex-col justify-center text-center transform transition duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-purple-500 text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                        <i class="fa-solid fa-box text-xl"></i>
                    </div>
                    <p class="text-sm font-extrabold text-purple-900 mb-1">Total Produk</p>
                    <p class="text-3xl font-black text-purple-700">{{ \App\Models\Product::count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 shadow-sm flex flex-col justify-center text-center transform transition duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                    <p class="text-sm font-extrabold text-blue-900 mb-1">Pengguna Terdaftar</p>
                    <p class="text-3xl font-black text-blue-700">{{ \App\Models\User::count() }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-hourglass-half text-orange-500"></i>
                    <h2 class="text-lg font-extrabold text-gray-800">Menunggu Verifikasi</h2>
                </div>
                <div class="p-0">
                    @if(isset($pendingShops) && $pendingShops->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($pendingShops as $shop)
                                <div class="px-6 py-5 flex justify-between items-center hover:bg-orange-50/30 transition-colors">
                                    <div>
                                        <p class="font-extrabold text-gray-900 text-sm mb-1 text-a11y-admin">{{ $shop->name }}</p>
                                        <p class="text-xs font-bold text-gray-500"><i class="fa-solid fa-user mr-1"></i> {{ $shop->user->name }} &bull; <i class="fa-solid fa-map-location-dot mx-1"></i> {{ $shop->district->name }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.shops.approve', $shop->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-a11y-admin px-4 py-2 inline-flex items-center text-xs font-bold rounded-lg shadow-sm transition-transform hover:scale-105 bg-emerald-400 text-white hover:bg-emerald-500">
                                                <i class="fa-solid fa-check mr-1"></i> Terima
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.shops.reject', $shop->id) }}" method="POST" id="form-reject-{{ $shop->id }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="rejected_reason" id="reason-{{ $shop->id }}">
                                            <button type="button" onclick="rejectShop({{ $shop->id }})" class="btn-a11y-admin px-4 py-2 inline-flex items-center text-xs font-bold rounded-lg shadow-sm transition-transform hover:scale-105 bg-rose-400 text-white hover:bg-rose-500">
                                                <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                            </button>
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

    <script>
        function rejectShop(shopId) {
            let reason = prompt("Silakan masukkan alasan mengapa toko ini ditolak:");
            if (reason === null) return; // User cancelled
            if (reason.trim() === "") {
                alert("Alasan penolakan wajib diisi!");
                return;
            }
            document.getElementById('reason-' + shopId).value = reason;
            document.getElementById('form-reject-' + shopId).submit();
        }
    </script>
</body>
</html>

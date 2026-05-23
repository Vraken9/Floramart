<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Toko - FloraMart Admin</title>
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
                    <a href="{{ route('admin.shops.index') }}" class="flex items-center px-3 py-2.5 bg-red-50 text-red-700 rounded-lg font-bold">
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
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900">Edit Profil Toko (Admin)</h1>
                <a href="{{ route('admin.shops.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form action="{{ route('admin.shops.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Toko</label>
                            <input type="text" name="name" value="{{ old('name', $shop->name) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp</label>
                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $shop->whatsapp_number) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Detail Alamat</label>
                            <textarea name="address_detail" rows="3" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" required>{{ old('address_detail', $shop->address_detail) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Toko</label>
                            <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">{{ old('description', $shop->description) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Logo Toko (Opsional)</label>
                            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

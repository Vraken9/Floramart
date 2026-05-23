<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - FloraMart Admin</title>
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
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-chart-pie w-6"></i> Ringkasan
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-store w-6"></i> Kelola Toko
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 bg-red-50 text-red-700 rounded-lg font-bold">
                        <i class="fa-solid fa-box w-6"></i> Kelola Produk
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-users w-6"></i> Kelola User
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-chart-line w-6"></i> Analitik
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-grow space-y-6" x-data="{
            shops: {{ $shops->toJson() }},
            selectedShopId: '',
            get selectedShop() {
                return this.shops.find(s => s.id == this.selectedShopId) || null;
            }
        }">
            <h1 class="text-2xl font-extrabold text-gray-900">Manajemen Produk</h1>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Toko untuk Mengelola Produknya</label>
                <select x-model="selectedShopId" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 max-w-md">
                    <option value="">-- Pilih Toko --</option>
                    <template x-for="shop in shops" :key="shop.id">
                        <option :value="shop.id" x-text="shop.name"></option>
                    </template>
                </select>
            </div>

            <template x-if="selectedShop">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800">Daftar Produk di <span x-text="selectedShop.name" class="text-red-700"></span></h2>
                        <a :href="'/admin/products/create?shop_id=' + selectedShop.id" class="px-4 py-2 bg-red-700 text-white text-sm font-bold rounded-lg hover:bg-red-800 transition-colors">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Produk
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="px-6 py-4 font-bold border-b border-gray-200">Gambar</th>
                                    <th class="px-6 py-4 font-bold border-b border-gray-200">Nama Produk</th>
                                    <th class="px-6 py-4 font-bold border-b border-gray-200">Harga</th>
                                    <th class="px-6 py-4 font-bold border-b border-gray-200">Status</th>
                                    <th class="px-6 py-4 font-bold border-b border-gray-200 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="product in selectedShop.products" :key="product.id">
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <img :src="product.image_path.startsWith('http') ? product.image_path : '/storage/' + product.image_path" class="w-12 h-12 rounded object-cover">
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-900" x-text="product.name"></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-semibold text-gray-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(product.price)"></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border"
                                                :class="product.is_hidden_by_admin ? 'bg-red-100 text-red-700 border-red-200' : (product.is_active ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-700 border-gray-200')"
                                                x-text="product.is_hidden_by_admin ? 'Hidden' : (product.is_active ? 'Aktif' : 'Nonaktif')">
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a :href="'/admin/products/' + product.id + '/edit'" class="px-3 py-1.5 text-xs font-bold rounded-lg border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 transition-colors inline-block">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </a>
                                                <form :action="'/admin/products/' + product.id + '/hide'" method="POST" class="inline-block">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg border bg-white text-orange-600 border-orange-200 hover:bg-orange-50 transition-colors inline-block" x-text="product.is_hidden_by_admin ? 'Tampilkan' : 'Sembunyikan'"></button>
                                                </form>
                                                <form :action="'/admin/products/' + product.id" method="POST" class="inline-block">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus permanen produk ini?')" class="px-3 py-1.5 text-xs font-bold rounded-lg border bg-white text-red-600 border-red-200 hover:bg-red-50 transition-colors inline-block">
                                                        <i class="fa-solid fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="selectedShop.products.length === 0">
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Toko ini belum memiliki produk.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </main>
    </div>
</body>
</html>

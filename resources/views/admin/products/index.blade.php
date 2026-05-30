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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sticky top-24">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 px-3">Navigasi Utama</div>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Ringkasan
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Kelola Toko
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 bg-gray-50 text-[#7c4959] rounded-lg font-medium text-sm">
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

        <main class="flex-grow space-y-8" x-data="{
            shops: {{ $shops->toJson() }},
            selectedShopId: '',
            get selectedShop() {
                return this.shops.find(s => s.id == this.selectedShopId) || null;
            }
        }">
            <div class="border-b border-gray-200 pb-4">
                <h1 class="text-2xl font-light text-gray-900 tracking-tight">Manajemen <span class="font-bold text-[#7c4959]">Produk</span></h1>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-2">Pilih Toko untuk Mengelola Produknya</label>
                <select x-model="selectedShopId" class="w-full rounded-lg border-gray-200 text-sm focus:border-[#7c4959] focus:ring-[#7c4959] max-w-md">
                    <option value="">-- Pilih Toko --</option>
                    <template x-for="shop in shops" :key="shop.id">
                        <option :value="shop.id" x-text="shop.name"></option>
                    </template>
                </select>
            </div>

            <template x-if="selectedShop">
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50">
                        <h3 class="text-lg font-extrabold text-gray-800"><i class="fa-solid fa-boxes-stacked mr-2 text-blue-500"></i> Katalog Produk Global</h3>
                        <a :href="'/admin/products/create?shop_id=' + selectedShop.id" class="btn-a11y-admin px-4 py-2 bg-[#7c4959] text-white text-xs font-bold rounded-lg hover:bg-[#5d3642] transition-colors">
                            Tambah Produk
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto p-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-100 rounded-lg">
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider rounded-tl-lg rounded-bl-lg">Detail Produk & Toko</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider">Kategori & Harga</th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-extrabold text-gray-600 uppercase tracking-wider">Visual</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-extrabold text-gray-600 uppercase tracking-wider rounded-tr-lg rounded-br-lg">Manajemen</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <template x-for="product in selectedShop.products" :key="product.id">
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="flex-shrink-0 h-14 w-14 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 shadow-sm">
                                                    <img :src="product.image_path.startsWith('http') ? product.image_path : '/storage/' + product.image_path" class="h-14 w-14 object-cover">
                                                </div>
                                                <div>
                                                    <p class="font-extrabold text-sm text-gray-900 text-a11y-admin" x-text="product.name"></p>
                                                    <p class="text-xs font-bold text-gray-500 mt-1"><i class="fa-solid fa-store text-purple-500"></i> <span x-text="selectedShop.name"></span></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-extrabold text-gray-800 text-a11y-admin mb-1" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(product.price)"></p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                        <form :action="'/admin/products/' + product.id + '/toggle'" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Ubah status visibilitas produk ini?')" class="btn-a11y-admin px-4 py-2 inline-flex text-xs font-bold rounded-lg shadow-sm transition-transform hover:scale-105 text-white" :class="product.is_active ? 'bg-[#926a7a] hover:bg-[#7c4959]' : 'bg-[#7c4959] hover:bg-[#5d3642]'">
                                                <template x-if="product.is_active">
                                                    <span><i class="fa-solid fa-eye mr-1"></i> Ditampilkan</span>
                                                </template>
                                                <template x-if="!product.is_active">
                                                    <span><i class="fa-solid fa-eye-slash mr-1"></i> Disembunyikan</span>
                                                </template>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a :href="'/admin/products/' + product.id + '/edit'" class="btn-a11y-admin flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-[#ac9a9c] rounded-lg hover:bg-[#926a7a] transition-colors shadow-sm"><i class="fa-solid fa-pen-to-square mr-1"></i> Edit</a>
                                            <form :action="'/admin/products/' + product.id" method="POST" class="inline-block">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus produk ini secara permanen dari sistem?')" class="btn-a11y-admin flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-[#7c4959] rounded-lg hover:bg-[#5d3642] transition-colors shadow-sm">
                                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    </tr>
                                </template>
                                <tr x-show="selectedShop.products.length === 0">
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">Toko ini belum memiliki produk.</td>
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

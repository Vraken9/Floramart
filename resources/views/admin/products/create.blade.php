<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - FloraMart Admin</title>
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
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-chart-line w-6"></i> Analitik
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-grow space-y-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900">Tambah Produk di Toko: {{ $shop->name }}</h1>
                <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" x-data="{
                    uploadType: 'file',
                    imagePreview: 'https://placehold.co/400x400/f3f4f6/a1a1aa?text=Preview+Foto+Bunga',
                    handleFileChange(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.imagePreview = URL.createObjectURL(file);
                        }
                    }
                }">
                    @csrf
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Produk</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                                <select name="category_id" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Harga (Rp)</label>
                                <input type="number" name="price" value="{{ old('price') }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
                                <textarea name="description" rows="5" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" required>{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Metode Upload Gambar</label>
                            
                            <div class="flex gap-4 mb-4">
                                <label class="flex items-center gap-2">
                                    <input type="radio" x-model="uploadType" value="file" name="upload_method" class="text-red-600 focus:ring-red-500">
                                    <span class="text-sm">Upload File Lisan</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" x-model="uploadType" value="url" name="upload_method" class="text-red-600 focus:ring-red-500">
                                    <span class="text-sm">Gunakan Link URL</span>
                                </label>
                            </div>

                            <div x-show="uploadType === 'file'" class="mt-2">
                                <label class="block text-xs text-gray-500 mb-1">Pilih File Foto</label>
                                <input type="file" name="image_file" accept="image/*" @change="handleFileChange" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            </div>

                            <div x-show="uploadType === 'url'" class="mt-2">
                                <label class="block text-xs text-gray-500 mb-1">Link Gambar</label>
                                <input type="url" name="image_url" placeholder="https://..." x-on:input="imagePreview = $event.target.value" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                            </div>

                            <div class="mt-4">
                                <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Preview Gambar</p>
                                <div class="w-full aspect-square bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                                    <img :src="imagePreview" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-red-700 hover:bg-red-800 text-white font-bold rounded-lg transition-colors">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

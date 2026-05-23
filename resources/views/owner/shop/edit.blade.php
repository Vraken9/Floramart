<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Toko - FloraMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <x-navigation />

    <main class="max-w-4xl mx-auto px-4 py-10">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-6 border-b pb-4"><i class="fa-solid fa-store text-[#7c4959] mr-2"></i> Pengaturan Profil Toko</h1>
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg font-semibold flex items-center">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('owner.shop.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex items-center space-x-6">
                    <div class="shrink-0">
                        <img class="h-24 w-24 object-cover rounded-full border-4 border-gray-100 shadow-sm" 
                             src="{{ str_starts_with($shop->logo_path ?? '', 'http') ? $shop->logo_path : asset('storage/' . ($shop->logo_path ?? 'default.png')) }}" 
                             alt="Logo Toko">
                    </div>
                    <label class="block">
                        <span class="sr-only">Pilih Logo Baru</span>
                        <input type="file" name="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-[#d4ccc0]/30 file:text-[#7c4959] hover:file:bg-[#d4ccc0]/50 transition-colors"/>
                        <p class="text-xs text-gray-400 mt-2">JPG, PNG maksimal 2MB.</p>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Toko</label>
                        <input type="text" name="name" value="{{ old('name', $shop->name) }}" required class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-[#7c4959] focus:ring-[#7c4959]">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $shop->whatsapp_number) }}" required class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-[#7c4959] focus:ring-[#7c4959]">
                        <p class="text-xs text-gray-500 mt-1">Gunakan format: 628xxx atau 08xxx</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap (Jalan / Patokan)</label>
                    <input type="text" name="address_detail" value="{{ old('address_detail', $shop->address_detail) }}" required class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-[#7c4959] focus:ring-[#7c4959]">
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5" x-data="{ isBranch: {{ old('is_branch', $shop->is_branch) ? 'true' : 'false' }} }">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_branch" value="1" x-model="isBranch" class="w-5 h-5 text-[#7c4959] border-gray-300 rounded focus:ring-[#7c4959]">
                        <span class="text-sm font-bold text-gray-800">Toko ini adalah cabang dari toko lain</span>
                    </label>

                    <div x-show="isBranch" class="mt-4 pt-4 border-t border-gray-200" style="display: none;">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Toko Utama (Pusat)</label>
                        <select name="parent_shop_id" class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-[#7c4959] focus:ring-[#7c4959]" :required="isBranch">
                            <option value="">-- Pilih Toko Pusat --</option>
                            @foreach($parentShops as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_shop_id', $shop->parent_shop_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }} ({{ $parent->district->name ?? 'Lokasi tidak diketahui' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Hanya toko yang sudah diverifikasi dan bukan cabang yang dapat dipilih.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi & Slogan Toko</label>
                    <textarea name="description" rows="4" class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-[#7c4959] focus:ring-[#7c4959]">{{ old('description', $shop->description) }}</textarea>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-[#7c4959] hover:bg-[#5d3642] text-white px-6 py-2.5 rounded-lg font-bold shadow-md transition-colors">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

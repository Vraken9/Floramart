@php
if (!function_exists('formatRupiah')) {
    function formatRupiah($angka){
        return 'Rp ' . number_format($angka,0,',','.');
    }
}
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Bunga - FloraMart</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-plum { background-color: #7c4959; }
        .text-plum { color: #7c4959; }
        .border-plum { border-color: #7c4959; }
        .bg-warm-beige { background-color: #d4ccc0; }
        .hover-bg-plum-dark:hover { background-color: #5d3642; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold text-plum flex items-center gap-2">
                        Flora<span class="text-[#926a7a]">Mart</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-plum font-semibold">Beranda</a>
                    <a href="{{ route('katalog.index') }}" class="text-gray-900 border-b-2 border-plum font-semibold">Katalog Bunga</a>
                    <a href="{{ route('shops.index') }}" class="text-gray-500 hover:text-plum font-semibold">Toko Florist</a>
                    @if (Auth::check())
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-plum font-semibold border-l pl-4 border-gray-300">Dasbor</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-plum font-semibold border-l pl-4 border-gray-300">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-plum hover-bg-plum-dark text-white px-4 py-2 rounded-md text-xs font-semibold uppercase shadow-sm">Daftar</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

        <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8" x-data="{ 
            regencies: {{ isset($regencies) ? $regencies->toJson() : '[]' }},
            selectedRegency: '{{ request('regency') }}',
            selectedDistrict: '{{ request('district') }}',
            get districts() {
                if (!this.selectedRegency) return [];
                const regency = this.regencies.find(r => r.id == this.selectedRegency);
                return regency ? regency.districts : [];
            }
        }">
            <form method="GET" action="{{ route('katalog.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Cari Bunga</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Mawar..." class="w-full pl-10 pr-4 py-2.5 rounded-md border border-gray-300 focus:outline-none focus:border-[#7c4959] focus:ring-1 focus:ring-[#7c4959] text-sm">
                        <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Kategori</label>
                    <select name="category" class="block w-full border-gray-300 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md text-sm py-2.5">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Kabupaten/Kota</label>
                    <select name="regency" x-model="selectedRegency" @change="selectedDistrict = ''" class="block w-full border-gray-300 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md text-sm py-2.5">
                        <option value="">Semua Kabupaten</option>
                        <template x-for="reg in regencies" :key="reg.id">
                            <option :value="reg.id" x-text="reg.name" :selected="reg.id == selectedRegency"></option>
                        </template>
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <div class="flex-grow">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Kecamatan</label>
                        <select name="district" x-model="selectedDistrict" :disabled="!selectedRegency" class="block w-full border-gray-300 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md text-sm py-2.5 disabled:bg-gray-100 disabled:text-gray-400">
                            <option value="">Semua Kecamatan</option>
                            <template x-for="dist in districts" :key="dist.id">
                                <option :value="dist.id" x-text="dist.name" :selected="dist.id == selectedDistrict"></option>
                            </template>
                        </select>
                    </div>
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2.5 bg-[#7c4959] text-white rounded-md font-bold text-sm uppercase hover:bg-[#5d3642] transition-colors">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>

        @if(isset($isSearch) && $isSearch)
            <div class="mb-6 flex justify-between items-center border-b border-[#d4ccc0]/50 pb-4">
                <h2 class="text-xl font-bold text-[#7c4959]">Hasil Pencarian Bunga</h2>
                <a href="{{ route('katalog.index') }}" class="text-sm font-semibold text-gray-500 hover:text-[#7c4959]">Reset Filter &times;</a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col group relative">
                        @if(Auth::check())
                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                @csrf
                                @php $isFavorited = Auth::user()->favoriteProducts()->where('product_id', $product->id)->exists(); @endphp
                                <button type="submit" class="h-8 w-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm hover:scale-110 transition-transform">
                                    <i class="fa-{{ $isFavorited ? 'solid' : 'regular' }} fa-heart {{ $isFavorited ? 'text-red-500' : 'text-gray-400' }}"></i>
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-[#d4ccc0]/20">
                            @if($product->image_path)
                                <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-image text-4xl"></i></div>
                            @endif
                        </a>

                        <div class="p-4 flex flex-col flex-grow">
                            <div class="text-xs font-semibold text-[#7c4959] mb-1 tracking-wide uppercase">{{ $product->category->name ?? 'Kategori' }}</div>
                            <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-gray-800 leading-tight mb-2 hover:text-[#7c4959] line-clamp-2">{{ $product->name }}</a>
                            <div class="mt-auto">
                                <div class="text-lg font-bold text-gray-900 mb-3">{{ formatRupiah($product->price) }}</div>
                                <hr class="border-gray-100 mb-3">
                                <div class="flex items-center text-xs text-gray-500 gap-1.5 line-clamp-1">
                                    <img src="{{ str_starts_with($product->shop->logo_path ?? '', 'http') ? $product->shop->logo_path : asset('storage/' . ($product->shop->logo_path ?? 'default-avatar.png')) }}" class="w-4 h-4 rounded-full object-cover">
                                    <i class="fa-solid fa-store text-gray-400"></i> <a href="{{ route('shop.show', $product->shop->id) }}" class="hover:text-[#7c4959]">{{ $product->shop->name ?? 'Toko' }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-xl border border-gray-200">
                        Tidak ada bunga yang ditemukan sesuai kriteria pencarian Anda.
                    </div>
                @endforelse
            </div>
        @else
            <div class="space-y-12">
                @foreach($groupedProducts as $category)
                    @if($category->products->isNotEmpty())
                        <section>
                            <div class="flex justify-between items-end mb-6 border-b border-[#d4ccc0]/50 pb-2">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $category->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $category->description ?? 'Rekomendasi kusuma terbaik' }}</p>
                                </div>
                                <a href="{{ route('katalog.index', ['category' => $category->id]) }}" class="text-sm font-semibold text-[#7c4959] hover:text-[#5d3642] flex items-center gap-1 transition-colors">
                                    Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach($category->products as $product)
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col group relative">
                                        @if(Auth::check())
                                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                                @csrf
                                                @php $isFavorited = Auth::user()->favoriteProducts()->where('product_id', $product->id)->exists(); @endphp
                                                <button type="submit" class="h-8 w-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm hover:scale-110 transition-transform">
                                                    <i class="fa-{{ $isFavorited ? 'solid' : 'regular' }} fa-heart {{ $isFavorited ? 'text-red-500' : 'text-gray-400' }}"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-[#d4ccc0]/20">
                                            @if($product->image_path)
                                                <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-image text-4xl"></i></div>
                                            @endif
                                        </a>

                                        <div class="p-4 flex flex-col flex-grow">
                                            <div class="text-xs font-semibold text-[#7c4959] mb-1 tracking-wide uppercase">{{ $product->category->name ?? 'Kategori' }}</div>
                                            <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-gray-800 leading-tight mb-2 hover:text-[#7c4959] line-clamp-2">{{ $product->name }}</a>
                                            <div class="mt-auto">
                                                <div class="text-lg font-bold text-gray-900 mb-3">{{ formatRupiah($product->price) }}</div>
                                                <hr class="border-gray-100 mb-3">
                                                <div class="flex items-center text-xs text-gray-500 gap-1.5 line-clamp-1">
                                                    <img src="{{ str_starts_with($product->shop->logo_path ?? '', 'http') ? $product->shop->logo_path : asset('storage/' . ($product->shop->logo_path ?? 'default-avatar.png')) }}" class="w-4 h-4 rounded-full object-cover">
                                                    <i class="fa-solid fa-store text-gray-400"></i> <a href="{{ route('shop.show', $product->shop->id) }}" class="hover:text-[#7c4959]">{{ $product->shop->name ?? 'Toko' }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach
            </div>
        @endif
    </main>

    <footer class="bg-[#ac9a9c]/10 pt-12 pb-8 border-t border-[#d4ccc0] mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <span class="text-2xl font-extrabold text-plum">Flora<span class="text-[#926a7a]">Mart</span></span>
                    <p class="mt-4 text-sm text-gray-600">Menghantarkan semerbak kasih dan kehangatan ke setiap sudut ruang. Lebih dari sekadar niaga, ini adalah simfoni keindahan dari Banjarnegara.</p>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-plum uppercase tracking-wider">Layanan</h3>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li>Garansi Kesegaran 100%</li>
                        <li>Pengiriman Instan Florist</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-plum uppercase tracking-wider">Kontak</h3>
                    <ul class="mt-4 space-y-2 text-sm text-gray-600">
                        <li>Jl. Klampok Gombong, Banjarnegara</li>
                        <li>WA: 0895-3012-3608</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-[#d4ccc0]/50 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} FloraMart Indonesia. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>

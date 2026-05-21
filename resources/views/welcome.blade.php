@php
// Helper untuk format Rupiah
if (!function_exists('formatRupiah')) {
    function formatRupiah($angka){
        return 'Rp ' . number_format($angka,0,',','.');
    }
}
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floramart - Marketplace Bunga Terpercaya</title>
    <!-- Tailwind CSS (via CDN untuk testing, disarankan via NPM untuk production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-plum { background-color: #6B21A8; }
        .text-plum { color: #6B21A8; }
        .border-plum { border-color: #6B21A8; }
        .bg-warm-beige { background-color: #FAF4EB; }
        .hover-bg-plum-dark:hover { background-color: #581C87; }
        .hero-pattern {
            background-color: #FAF4EB;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236b21a8' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- Top Navigation -->
    <nav x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold text-plum flex items-center gap-2">
                        <i class="fa-solid fa-seedling"></i>
                        Floramart
                    </a>
                </div>

                <!-- Desktop Search -->
                <div class="hidden md:flex flex-1 justify-center px-8">
                    <form action="{{ route('home') }}" method="GET" class="w-full max-w-lg relative">
                        <input type="text" name="search" placeholder="Cari bunga, toko, atau momen..."
                               class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-plum focus:border-transparent text-sm">
                        <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </form>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    @if(Auth::check())
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-plum transition font-medium">
                            <i class="fa-solid fa-user-circle mr-1"></i> Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 transition font-medium">
                                <i class="fa-solid fa-sign-out-alt mr-1"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-plum transition font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-plum hover-bg-plum-dark text-white px-5 py-2 rounded-full text-sm font-semibold shadow-sm transition">Daftar</a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-plum focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Search (Visible only on small screens) -->
            <div class="md:hidden pb-3">
                <form action="{{ route('home') }}" method="GET" class="relative">
                    <input type="text" name="search" placeholder="Cari bunga..."
                           class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-plum text-sm">
                    <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </form>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t border-gray-100">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-plum hover:bg-warm-beige">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-plum hover:bg-warm-beige">Masuk</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-plum font-bold hover:bg-warm-beige">Daftar</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Content Area -->
    <main class="flex-grow">
        @if(!$isSearch)
            <!-- Hero Section -->
            <div class="hero-pattern py-16 lg:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">
                        Temukan Bunga Segar untuk <br class="hidden lg:block"/><span class="text-plum">Setiap Momen Spesialmu</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                        Floramart menghubungkanmu dengan ribuan toko bunga lokal terbaik di seluruh Indonesia. Pesan hari ini, mekar hari ini!
                    </p>
                </div>
            </div>
        @endif

        <!-- Filter / Search Result Info -->
        @if($isSearch)
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Hasil Pencarian</h2>
                <p class="text-gray-500 mb-6">Menampilkan {{ $products->count() }} produk yang sesuai dengan filter Anda.</p>

                @if($products->isEmpty())
                    <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
                        <i class="fa-solid fa-seedling text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Maaf, kami tidak menemukan bunga yang Anda cari saat ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            <!-- INLINE CARD Search Results -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col group relative">
                                <!-- Wishlist Button -->
                                @if(Auth::check())
                                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                        @csrf
                                        @php
                                            $isFavorited = Auth::user()->favoriteProducts()->where('product_id', $product->id)->exists();
                                        @endphp
                                        <button type="submit" class="h-8 w-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm hover:scale-110 transition-transform">
                                            <i class="fa-{{ $isFavorited ? 'solid' : 'regular' }} fa-heart {{ $isFavorited ? 'text-red-500' : 'text-gray-400' }}"></i>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-warm-beige">
                                    @if($product->image_url)
                                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-image text-4xl"></i>
                                        </div>
                                    @endif
                                </a>

                                <div class="p-4 flex flex-col flex-grow">
                                    <div class="text-xs font-semibold text-plum mb-1 tracking-wide uppercase">{{ $product->category->name ?? 'Kategori' }}</div>
                                    <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-gray-800 leading-tight mb-2 hover:text-plum line-clamp-2">
                                        {{ $product->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 mb-3 line-clamp-1">
                                        {{ Str::limit($product->description, 50) }}
                                    </p>

                                    <div class="mt-auto">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-lg font-bold text-gray-900">{{ formatRupiah($product->price) }}</span>
                                        </div>

                                        <hr class="border-gray-100 mb-3">

                                        <div class="flex items-center text-xs text-gray-500 gap-1.5">
                                            <i class="fa-solid fa-store text-gray-400"></i>
                                            <span class="truncate">{{ $product->shop->name ?? 'Toko Tidak Diketahui' }}</span>
                                        </div>
                                        <div class="flex items-center text-[10px] text-gray-400 mt-1 gap-1.5">
                                            <i class="fa-solid fa-location-dot"></i>
                                            <span class="truncate">
                                                {{ $product->shop->district->name ?? '' }}, {{ $product->shop->district->regency->name ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <!-- Kategori / Normal View -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                @foreach($groupedProducts as $category)
                    @if($category->products->isNotEmpty())
                        <div class="mb-12">
                            <div class="flex justify-between items-end mb-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $category->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $category->description ?? 'Rekomendasi terbaik untuk kategori ini' }}</p>
                                </div>
                                <a href="{{ route('home', ['category' => $category->id]) }}" class="text-sm font-semibold text-plum hover:text-purple-900 flex items-center gap-1">
                                    Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach($category->products as $product)
                                    <!-- INLINE CARD (Since component might not exist) -->
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col group relative">
                                        <!-- Wishlist Button -->
                                        @if(Auth::check())
                                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                                @csrf
                                                @php
                                                    $isFavorited = Auth::user()->favoriteProducts()->where('product_id', $product->id)->exists();
                                                @endphp
                                                <button type="submit" class="h-8 w-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm hover:scale-110 transition-transform">
                                                    <i class="fa-{{ $isFavorited ? 'solid' : 'regular' }} fa-heart {{ $isFavorited ? 'text-red-500' : 'text-gray-400' }}"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-warm-beige">
                                            @if($product->image_url)
                                                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i class="fa-solid fa-image text-4xl"></i>
                                                </div>
                                            @endif
                                        </a>

                                        <div class="p-4 flex flex-col flex-grow">
                                            <div class="text-xs font-semibold text-plum mb-1 tracking-wide uppercase">{{ $product->category->name ?? 'Kategori' }}</div>
                                            <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-gray-800 leading-tight mb-2 hover:text-plum line-clamp-2">
                                                {{ $product->name }}
                                            </a>
                                            <p class="text-xs text-gray-500 mb-3 line-clamp-1">
                                                {{ Str::limit($product->description, 50) }}
                                            </p>

                                            <div class="mt-auto">
                                                <div class="flex justify-between items-center mb-3">
                                                    <span class="text-lg font-bold text-gray-900">{{ formatRupiah($product->price) }}</span>
                                                </div>

                                                <hr class="border-gray-100 mb-3">

                                                <div class="flex items-center text-xs text-gray-500 gap-1.5">
                                                    <i class="fa-solid fa-store text-gray-400"></i>
                                                    <span class="truncate">{{ $product->shop->name ?? 'Toko Tidak Diketahui' }}</span>
                                                </div>
                                                <div class="flex items-center text-[10px] text-gray-400 mt-1 gap-1.5">
                                                    <i class="fa-solid fa-location-dot"></i>
                                                    <span class="truncate">
                                                        {{ $product->shop->district->name ?? '' }}, {{ $product->shop->district->regency->name ?? '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END INLINE CARD -->
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2 text-xl font-bold text-plum">
                    <i class="fa-solid fa-seedling"></i> Floramart
                </div>
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Floramart. Marketplace Bunga Indonesia.</p>
                <div class="flex gap-4 text-gray-400">
                    <a href="#" class="hover:text-plum"><i class="fa-brands fa-instagram text-xl"></i></a>
                    <a href="#" class="hover:text-plum"><i class="fa-brands fa-whatsapp text-xl"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shop->name }} - FloraMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-plum { color: #7c4959; }
        .bg-plum { background-color: #7c4959; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <x-navigation />

    <main class="max-w-7xl mx-auto px-4 py-10">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 mb-10">
    <div class="flex flex-col md:flex-row items-center gap-6">
        <img src="{{ str_starts_with($shop->logo_path ?? '', 'http') ? $shop->logo_path : asset('storage/' . ($shop->logo_path ?? 'default.png')) }}" 
             class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-md">
        <div class="flex-grow text-center md:text-left">
            <h1 class="text-3xl font-extrabold text-gray-900">{{ $shop->name }}</h1>
            <div class="flex items-center gap-4 mt-2 justify-center md:justify-start">
                <p class="text-gray-500"><i class="fa-solid fa-location-dot text-[#7c4959]"></i> {{ $shop->district->name }}, {{ $shop->district->regency->name }}</p>
                <div class="flex items-center text-sm font-bold text-gray-700 bg-yellow-50 px-2 py-1 rounded border border-yellow-200" title="Rating Rata-rata">
                    <i class="fa-solid fa-star text-yellow-400 mr-1"></i> {{ $shop->average_rating }}
                </div>
            </div>
            <p class="text-gray-600 mt-4 max-w-2xl italic">"{{ $shop->description }}"</p>
            
            <div class="mt-5 flex flex-wrap gap-3 justify-center md:justify-start">
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full border border-green-200 flex items-center">
                    <i class="fa-solid fa-check-circle mr-1"></i> Toko Terverifikasi
                </span>
                @if($shop->is_branch && $shop->parentShop)
                    <a href="{{ route('shop.show', $shop->parentShop->id) }}" class="px-3 py-1 bg-purple-100 hover:bg-purple-200 text-purple-800 text-xs font-bold rounded-full border border-purple-200 flex items-center transition-colors">
                        <i class="fa-solid fa-code-branch mr-1"></i> Cabang Resmi: {{ $shop->parentShop->name }}
                    </a>
                @endif
                <a href="https://wa.me/{{ $shop->whatsapp_number }}?text=Halo%20{{ $shop->name }},%20saya%20ingin%20bertanya%20mengenai%20produk%20toko%20Anda." 
                   target="_blank" 
                   class="px-4 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-full transition-colors flex items-center">
                    <i class="fa-brands fa-whatsapp mr-1.5"></i> Hubungi via WA
                </a>
            </div>
        </div>
    </div>
</div>

        @foreach($groupedProducts as $categoryName => $products)
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-[#7c4959] pl-4">{{ $categoryName }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col group relative">
                            @if(!Auth::check() || Auth::user()->role !== 'admin')
                                @if(Auth::check())
                                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 right-3 z-10">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm transition-transform hover:scale-110 {{ Auth::user()->favoriteProducts->contains($product->id) ? 'text-red-500' : 'text-gray-400 hover:text-red-400' }}">
                                            <i class="fa-solid fa-heart"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="absolute top-3 right-3 z-10 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-red-400 shadow-sm transition-transform hover:scale-110" title="Login untuk menyimpan favorit">
                                        <i class="fa-solid fa-heart"></i>
                                    </a>
                                @endif
                            @endif
                            <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}" class="aspect-square object-cover">
                            <div class="p-4 flex-grow">
                                <h3 class="font-bold text-gray-900">{{ $product->name }}</h3>
                                <p class="text-[#7c4959] font-extrabold mt-2">Rp {{ number_format($product->price,0,',','.') }}</p>
                            </div>
                            <div class="p-4 pt-0">
                                <a href="https://wa.me/{{ $shop->whatsapp_number ?? '6289530123608' }}?text=Halo%20{{ $shop->name }},%20saya%20tertarik%20dengan%20produk%20{{ $product->name }}" target="_blank" class="block w-full py-2.5 bg-green-600 hover:bg-green-700 text-white text-center font-bold text-sm rounded-lg transition-colors">
                                   <i class="fa-brands fa-whatsapp mr-1"></i> Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </main>
</body>
</html>
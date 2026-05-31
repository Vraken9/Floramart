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
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('katalog.index') }}" class="btn-a11y-admin inline-flex items-center px-4 py-2 bg-white border border-[#d4ccc0] text-sm font-bold text-gray-700 rounded-full shadow-sm hover:shadow hover:text-[#7c4959] hover:border-[#7c4959] transition-all mb-8 group">
        <svg class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform text-[#7c4959]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali
    </a>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-12 overflow-hidden relative">
        <!-- Banner Header -->
        <div class="h-40 md:h-48 w-full bg-gradient-to-r from-[#7c4959] via-[#926a7a] to-[#d4ccc0] relative">
            <div class="absolute inset-0 bg-white/20 backdrop-blur-[2px]"></div>
        </div>

        <div class="px-8 pb-8 pt-0 relative">
            <div class="flex flex-col md:flex-row items-center md:items-end gap-6 -mt-16 md:-mt-20 mb-6 relative z-10">
                <img src="{{ str_starts_with($shop->logo_path ?? '', 'http') ? $shop->logo_path : asset('images/' . ($shop->logo_path ?? 'default.png')) }}" 
                     class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-white shadow-lg bg-white">
                
                <div class="flex-grow text-center md:text-left mb-2 md:mb-0">
                    <h1 class="text-a11y-admin text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">{{ $shop->name }}</h1>
                    <div class="flex items-center gap-4 mt-2 justify-center md:justify-start">
                        <p class="text-gray-600 font-medium"><i class="fa-solid fa-location-dot text-[#7c4959] mr-1"></i> {{ $shop->district->name }}, {{ $shop->district->regency->name }}</p>
                        <div class="flex items-center text-sm font-bold text-gray-800 bg-yellow-100 px-2.5 py-1 rounded-full shadow-sm border border-yellow-200" title="Rating Rata-rata">
                            <i class="fa-solid fa-star text-yellow-500 mr-1.5"></i> {{ $shop->average_rating }}
                        </div>
                    </div>
                </div>

                <div class="md:ml-auto">
                    <a href="https://wa.me/{{ $shop->whatsapp_number }}?text=Halo%20{{ $shop->name }},%20saya%20ingin%20bertanya%20mengenai%20produk%20toko%20Anda." 
                       target="_blank" 
                       class="btn-a11y-admin px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-full shadow-md hover:shadow-lg transition-all flex items-center">
                        <i class="fa-brands fa-whatsapp text-lg mr-2"></i> Hubungi via WA
                    </a>
                </div>
            </div>

            <div class="mt-2 text-center md:text-left md:pl-48">
                <p class="text-gray-600 text-lg italic leading-relaxed max-w-3xl">"{{ $shop->description }}"</p>
                
                <div class="mt-6 flex flex-wrap gap-3 justify-center md:justify-start">
                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200 flex items-center shadow-sm">
                        <i class="fa-solid fa-certificate mr-1.5 text-emerald-500"></i> Toko Terverifikasi
                    </span>
                    @if($shop->is_branch && $shop->parentShop)
                        <a href="{{ route('shop.show', $shop->parentShop->id) }}" class="px-4 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold rounded-full border border-purple-200 flex items-center transition-colors shadow-sm">
                            <i class="fa-solid fa-code-branch mr-1.5 text-purple-500"></i> Cabang Resmi: {{ $shop->parentShop->name }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

        @foreach($groupedProducts as $categoryName => $products)
            <section class="mb-12">
                <h2 class="text-a11y-admin text-2xl font-bold text-gray-800 mb-6 border-l-4 border-[#7c4959] pl-4">{{ $categoryName }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-2xl shadow-sm border border-rose-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group relative">
                            <!-- Label Stok / Segar (Mockup) -->
                            <div class="absolute top-3 left-3 z-10">
                                <span class="bg-green-500 text-white text-[10px] font-extrabold uppercase px-2 py-1 rounded shadow-sm">Segar</span>
                            </div>
                            @if(!Auth::check() || Auth::user()->role === 'user')
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
                            <a href="{{ route('product.show', ['slug' => $product->slug, 'ref' => 'shop-' . $shop->id]) }}" class="block aspect-square overflow-hidden bg-rose-50 relative">
                                <img src="{{ ($product->image_path && str_starts_with($product->image_path, 'http')) ? $product->image_path : ($product->image_path ? asset('images/' . $product->image_path) : '') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out mix-blend-multiply" alt="{{ $product->name }}" onerror="this.style.display='none'">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </a>
                            <div class="p-5 flex-grow bg-white relative z-10 -mt-2 rounded-t-2xl">
                                <a href="{{ route('product.show', ['slug' => $product->slug, 'ref' => 'shop-' . $shop->id]) }}" class="text-a11y-admin font-extrabold text-gray-800 hover:text-[#7c4959] line-clamp-2 leading-tight mb-2">{{ $product->name }}</a>
                                <p class="text-a11y-admin text-[#7c4959] font-black text-lg">Rp {{ number_format($product->price,0,',','.') }}</p>
                            </div>
                            <div class="p-5 pt-0 flex gap-2 bg-white">
                                <a href="{{ route('product.show', ['slug' => $product->slug, 'ref' => 'shop-' . $shop->id]) }}" class="btn-a11y-admin flex-1 py-2.5 bg-rose-50 text-[#7c4959] hover:bg-[#7c4959] hover:text-white text-center font-bold text-xs rounded-xl transition-colors flex items-center justify-center">
                                   Detail
                                </a>
                                <a href="https://wa.me/{{ $shop->whatsapp_number ?? '6289530123608' }}?text=Halo%20{{ $shop->name }},%20saya%20tertarik%20dengan%20produk%20{{ $product->name }}" target="_blank" class="btn-a11y-admin flex-1 py-2.5 bg-green-500 hover:bg-green-600 text-white text-center font-bold text-xs rounded-xl shadow-md shadow-green-500/30 hover:shadow-lg hover:shadow-green-500/40 transition-all flex items-center justify-center" title="Pesan via WhatsApp">
                                   <i class="fa-brands fa-whatsapp text-sm mr-1"></i> Pesan
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
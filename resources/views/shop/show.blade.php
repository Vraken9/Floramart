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
            <p class="text-gray-500 mt-2"><i class="fa-solid fa-location-dot text-[#7c4959]"></i> {{ $shop->district->name }}, {{ $shop->district->regency->name }}</p>
            <p class="text-gray-600 mt-4 max-w-2xl italic">"{{ $shop->description }}"</p>
            
            <div class="mt-5 flex flex-wrap gap-3 justify-center md:justify-start">
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full border border-green-200 flex items-center">
                    <i class="fa-solid fa-check-circle mr-1"></i> Toko Terverifikasi
                </span>
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
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col group">
                            <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}" class="aspect-square object-cover">
                            <div class="p-4 flex-grow">
                                <h3 class="font-bold text-gray-900">{{ $product->name }}</h3>
                                <p class="text-[#7c4959] font-extrabold mt-2">Rp {{ number_format($product->price,0,',','.') }}</p>
                            </div>
                            <a href="https://wa.me/{{ $shop->whatsapp_number }}?text=Halo%20{{ $shop->name }},%20saya%20ingin%20memesan%20{{ $product->name }}" 
                               target="_blank" class="block w-full py-3 bg-green-600 hover:bg-green-700 text-white text-center font-bold text-sm transition-colors">
                               <i class="fa-brands fa-whatsapp mr-2"></i> Pesan Sekarang
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </main>
</body>
</html>
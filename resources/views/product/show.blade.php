<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - FloraMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    <x-navigation />


    @php
        // 1. Cek status wishlist jika user sedang login
        $isFavorited = false;
        if(Auth::check()) {
            $isFavorited = Auth::user()->favoriteProducts()->where('product_id', $product->id)->exists();
        }

        // 2. Format Nomor WhatsApp (Pastikan diawali 62)
        $waNumber = $product->shop->whatsapp_number;
        if(str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }

        // 3. Susun Pesan Otomatis (URL Encoded)
        $hargaFormat = number_format($product->price, 0, ',', '.');
        $pesanWa = urlencode("Halo {$product->shop->name}, saya tertarik untuk memesan bunga *{$product->name}* seharga Rp {$hargaFormat} yang saya lihat di FloraMart. Apakah masih bisa dipesan?");
        $waLink = "https://wa.me/{$waNumber}?text={$pesanWa}";
    @endphp

    <main class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @php
                $ref = request('ref');
                $backUrl = route('home');
                $backText = 'Kembali ke Beranda';
                
                if ($ref === 'katalog') {
                    $backUrl = route('katalog.index');
                    $backText = 'Kembali ke Katalog';
                } elseif (str_starts_with($ref, 'shop-')) {
                    $shopId = str_replace('shop-', '', $ref);
                    $backUrl = route('shop.show', $shopId);
                    $backText = 'Kembali ke Toko';
                }
            @endphp
            <a href="{{ $backUrl }}" class="btn-a11y-admin inline-flex items-center px-4 py-2 bg-white border border-[#d4ccc0] text-sm font-bold text-gray-700 rounded-full shadow-sm hover:shadow hover:text-[#7c4959] hover:border-[#7c4959] transition-all mb-8 group">
                <svg class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform text-[#7c4959]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ $backText }}
            </a>

            <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-[#7c4959] transition">Beranda</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('home', ['category' => $product->category_id]) }}" class="hover:text-[#7c4959] transition">{{ $product->category->name }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-gray-400 font-medium line-clamp-1">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-lg border border-rose-100 overflow-hidden mb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

                    <div class="bg-rose-50 p-4 md:p-12 flex items-center justify-center relative group">
                        @if($product->image_path && str_starts_with($product->image_path, 'http'))
                            <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[350px] md:max-h-[500px] object-contain rounded-xl md:rounded-2xl shadow-xl group-hover:scale-105 transition-transform duration-700 ease-in-out mix-blend-multiply">
                        @elseif($product->image_path)
                            <img src="{{ asset('images/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[350px] md:max-h-[500px] object-contain rounded-xl md:rounded-2xl shadow-xl group-hover:scale-105 transition-transform duration-700 ease-in-out mix-blend-multiply">
                        @else
                            <div class="w-full h-64 flex items-center justify-center bg-gray-200 rounded-xl"><i class="fa-solid fa-image text-5xl text-gray-400"></i></div>
                        @endif
                        <span class="absolute top-3 left-3 md:top-6 md:left-6 px-3 md:px-4 py-1 md:py-1.5 text-[10px] md:text-xs font-black tracking-widest uppercase bg-white/90 backdrop-blur-md text-[#7c4959] rounded-full shadow-md border border-rose-100">
                            {{ $product->category->name ?? '' }}
                        </span>
                    </div>

                    <div class="p-5 md:p-12 flex flex-col justify-center">
                        <h1 class="text-a11y-admin text-2xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">{{ $product->name }}</h1>

                        <div class="flex items-center text-sm text-gray-500 mb-6 pb-6 border-b border-gray-100">
                            <span class="font-medium text-[#7c4959] bg-[#7c4959]/10 px-2.5 py-0.5 rounded-full mr-3">Toko Terverifikasi</span>
                            <a href="{{ route('shop.show', $product->shop->id) }}" class="text-a11y-admin flex items-center hover:text-[#7c4959] hover:underline font-medium transition-colors">
                                <svg class="h-4 w-4 mr-1 text-[#926a7a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $product->shop->name }} ({{ $product->shop->district->name }})
                            </a>
                        </div>

                        <div class="mb-6 md:mb-8">
                            <p class="text-a11y-admin text-xs md:text-sm text-gray-500 mb-1 uppercase tracking-wider font-semibold">Harga Spesial</p>
                            <p class="text-a11y-admin text-2xl md:text-4xl font-extrabold text-gray-900">Rp {{ $hargaFormat }}</p>
                        </div>

                        <div class="mb-10">
                            <h3 class="text-a11y-admin text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Detail Produk</h3>
                            <p class="text-a11y-admin text-gray-600 leading-relaxed text-base">{{ $product->description }}</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                            <a href="{{ route('product.whatsapp', $product->id) }}" target="_blank" data-a11y="btn-order" class="btn-a11y-order flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 md:py-4 px-6 rounded-xl transition-all flex items-center justify-center text-sm md:text-lg shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                <i class="fa-brands fa-whatsapp mr-2 text-2xl"></i> Pesan Sekarang
                            </a>

                            @if(!Auth::check() || Auth::user()->role === 'user')
                                @if(Auth::check())
                                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="sm:w-auto">
                                        @csrf
                                        <button type="submit" class="btn-a11y-admin w-full h-full sm:w-16 inline-flex justify-center items-center px-4 py-4 bg-white border-2 border-[#d4ccc0] text-[#7c4959] rounded-xl hover:bg-[#d4ccc0]/10 hover:border-[#7c4959] transition-all focus:outline-none" title="Favorit">
                                            @if($isFavorited)
                                                <i class="fa-solid fa-heart text-2xl text-red-500"></i>
                                            @else
                                                <i class="fa-regular fa-heart text-2xl"></i>
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="sm:w-auto w-full h-full sm:w-16 inline-flex justify-center items-center px-4 py-4 bg-white border-2 border-[#d4ccc0] text-[#7c4959] rounded-xl hover:bg-[#d4ccc0]/10 hover:border-[#7c4959] transition-all focus:outline-none" title="Login untuk menyimpan favorit">
                                        <i class="fa-regular fa-heart text-2xl"></i>
                                    </a>
                                @endif
                            @endif
                        </div>

                        <div class="mt-8 grid grid-cols-3 gap-4 border-t border-gray-100 pt-8">
                            <div class="flex flex-col items-center text-center">
                                <span class="bg-[#d4ccc0]/20 p-2 rounded-full mb-2">🌿</span>
                                <span class="text-[10px] font-bold uppercase text-gray-500">100% Segar</span>
                            </div>
                            <div class="flex flex-col items-center text-center">
                                <span class="bg-[#d4ccc0]/20 p-2 rounded-full mb-2">🚚</span>
                                <span class="text-[10px] font-bold uppercase text-gray-500">Kirim Langsung</span>
                            </div>
                            <div class="flex flex-col items-center text-center">
                                <span class="bg-[#d4ccc0]/20 p-2 rounded-full mb-2">💬</span>
                                <span class="text-[10px] font-bold uppercase text-gray-500">Nego di WA</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Review Section -->
            <div class="mt-16 bg-white rounded-2xl shadow-sm border border-[#d4ccc0]/50 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-[#fdfbf7] flex items-center justify-between">
                    <h2 class="text-a11y-admin text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-star text-yellow-400"></i> Ulasan Pembeli
                        <span class="text-lg font-medium text-gray-500">({{ $product->average_rating }} / 5)</span>
                    </h2>
                    <span class="text-sm font-semibold text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">{{ $product->reviews->count() }} Ulasan</span>
                </div>

                <div class="p-8">
                    @auth
                        @php
                            $hasReviewed = $product->reviews->where('user_id', Auth::id())->first();
                        @endphp
                        
                        @if(!$hasReviewed)
                            <!-- Review Form -->
                            <div class="mb-10 bg-gray-50 rounded-xl p-6 border border-gray-100">
                                <h3 class="text-a11y-admin text-lg font-bold text-gray-900 mb-4">Tulis Ulasan Anda</h3>
                                <form action="{{ route('review.store', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="text-a11y-admin block text-sm font-semibold text-gray-700 mb-2">Penilaian</label>
                                        <div class="flex items-center gap-4" x-data="{ rating: 5, hoverRating: 0 }">
                                            <div class="flex">
                                                <template x-for="i in 5">
                                                    <i class="fa-star text-2xl cursor-pointer transition-colors"
                                                       :class="i <= (hoverRating || rating) ? 'fa-solid text-yellow-400' : 'fa-regular text-gray-300'"
                                                       @mouseover="hoverRating = i"
                                                       @mouseleave="hoverRating = 0"
                                                       @click="rating = i"></i>
                                                </template>
                                            </div>
                                            <input type="hidden" name="rating" x-model="rating" required>
                                            <span class="text-sm font-medium text-gray-500" x-text="rating + ' Bintang'"></span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="comment" class="text-a11y-admin block text-sm font-semibold text-gray-700 mb-2">Komentar (Opsional)</label>
                                        <textarea name="comment" id="comment" rows="3" class="input-a11y-admin w-full border-gray-300 rounded-lg shadow-sm focus:border-[#7c4959] focus:ring focus:ring-[#7c4959]/20" placeholder="Bagaimana pengalaman Anda dengan bunga ini?"></textarea>
                                    </div>
                                    <button type="submit" class="btn-a11y-admin px-6 py-2 bg-[#7c4959] hover:bg-[#5d3642] text-white font-bold rounded-lg shadow-sm transition-colors">
                                        Kirim Ulasan
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="mb-10 bg-blue-50 text-blue-800 rounded-xl p-4 flex items-center justify-between border border-blue-100">
                            <span class="text-sm font-medium">Ingin memberikan ulasan? Silakan masuk terlebih dahulu.</span>
                            <a href="{{ route('login') }}" class="text-sm font-bold bg-white px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">Masuk</a>
                        </div>
                    @endauth

                    <!-- Review List -->
                    @if($product->reviews->isEmpty())
                        <div class="text-center py-8">
                            <i class="fa-regular fa-comments text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium">Belum ada ulasan untuk produk ini. Jadilah yang pertama!</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($product->reviews()->latest()->get() as $review)
                                <div class="flex gap-4 pb-6 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                    <div class="w-10 h-10 rounded-full bg-[#d4ccc0]/30 flex items-center justify-center text-[#7c4959] font-bold shrink-0">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="font-bold text-gray-900">{{ $review->user->name }}</h4>
                                            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-star {{ $i <= $review->rating ? 'fa-solid text-yellow-400' : 'fa-regular text-gray-300' }} text-sm"></i>
                                            @endfor
                                        </div>
                                        @if($review->comment)
                                            <p class="text-gray-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>

    <footer class="bg-[#ac9a9c]/10 pt-16 pb-8 border-t border-[#d4ccc0] mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8">
                <div class="md:col-span-12 lg:col-span-5">
                    <span class="text-3xl font-extrabold text-[#7c4959] tracking-tight">Flora<span class="text-[#926a7a]">Mart</span></span>
                    <p class="mt-6 text-sm leading-relaxed text-gray-600 italic">
                        "Di bawah naungan semesta, FloraMart hadir merajut <span class="font-semibold text-[#7c4959]">kusuma</span> menjadi mahakarya. Kami menyatukan <span class="font-semibold text-[#7c4959]">karsa</span> para perajin lokal, menghantarkan semerbak kasih dan kehangatan ke setiap sudut ruang."
                    </p>
                </div>
                <div class="md:col-span-4 lg:col-span-3">
                    <h3 class="text-sm font-bold text-[#7c4959] tracking-wider uppercase">Layanan Pelanggan</h3>
                    <ul class="mt-4 space-y-3 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors">Garansi Kesegaran 100%</a></li>
                        <li><a href="#" class="hover:text-[#7c4959] transition-colors">Pusat Bantuan (FAQ)</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

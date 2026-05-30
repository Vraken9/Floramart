<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dasbor Manajemen Toko - FloraMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans flex flex-col min-h-screen">
    <x-navigation />

    <main class="flex-grow py-12">
        <div class="w-full max-w-[85%] 2xl:max-w-[1400px] mx-auto sm:px-6 lg:px-8">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-8">
                <h2 class="font-light text-2xl text-gray-900 dark:text-gray-100 tracking-tight flex items-center">
                    Dashboard <span class="font-bold text-[#7c4959] ml-1">Manajemen Toko</span>
                    @if($shop->status == 'approved')
                        <span class="ml-4 px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest bg-green-50 text-green-700 border border-green-100 rounded-full">Aktif</span>
                    @else
                        <span class="ml-4 px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest bg-yellow-50 text-yellow-700 border border-yellow-100 rounded-full">{{ $shop->status }}</span>
                    @endif
                </h2>
            </div>

            <!-- Analitik Toko -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
                <div class="bg-[#7c4959] rounded-2xl shadow-sm border border-[#5d3642] p-4 lg:p-6 flex flex-col justify-center text-center transform transition duration-300 hover:scale-105 hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 text-white rounded-full flex items-center justify-center mx-auto mb-2 lg:mb-3 shadow-sm backdrop-blur-sm">
                        <i class="fa-solid fa-box text-lg lg:text-xl"></i>
                    </div>
                    <p class="text-xs lg:text-sm font-bold text-white/90 mb-1">Total Produk</p>
                    <p class="text-2xl lg:text-3xl font-black text-white">{{ $products->where('is_active', true)->count() }}</p>
                </div>
                
                <div class="bg-[#926a7a] rounded-2xl shadow-sm border border-[#7c4959] p-4 lg:p-6 flex flex-col justify-center text-center transform transition duration-300 hover:scale-105 hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 text-white rounded-full flex items-center justify-center mx-auto mb-2 lg:mb-3 shadow-sm backdrop-blur-sm">
                        <i class="fa-solid fa-eye text-lg lg:text-xl"></i>
                    </div>
                    <p class="text-xs lg:text-sm font-bold text-white/90 mb-1">Kunjungan Toko</p>
                    <p class="text-2xl lg:text-3xl font-black text-white">{{ number_format($totalShopClicks ?? 0) }}</p>
                </div>
                
                <div class="bg-[#ac9a9c] rounded-2xl shadow-sm border border-[#926a7a] p-4 lg:p-6 flex flex-col justify-center text-center transform transition duration-300 hover:scale-105 hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 text-white rounded-full flex items-center justify-center mx-auto mb-2 lg:mb-3 shadow-sm backdrop-blur-sm">
                        <i class="fa-brands fa-whatsapp text-lg lg:text-xl"></i>
                    </div>
                    <p class="text-xs lg:text-sm font-bold text-white/90 mb-1">Klik WhatsApp</p>
                    <p class="text-2xl lg:text-3xl font-black text-white">{{ number_format($totalWaClicks ?? 0) }}</p>
                </div>
                
                <div class="bg-[#d4ccc0] rounded-2xl shadow-sm border border-[#ac9a9c] p-4 lg:p-6 flex flex-col justify-center text-center transform transition duration-300 hover:scale-105 hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white text-[#7c4959] rounded-full flex items-center justify-center mx-auto mb-2 lg:mb-3 shadow-sm">
                        <i class="fa-solid fa-heart text-lg lg:text-xl"></i>
                    </div>
                    <p class="text-xs lg:text-sm font-bold text-[#7c4959]/90 mb-1">Difavoritkan</p>
                    <p class="text-2xl lg:text-3xl font-black text-[#7c4959]">{{ number_format($totalFavorites ?? 0) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                <div class="p-4 lg:p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50">
                    <h3 class="text-base lg:text-lg font-extrabold text-gray-800"><i class="fa-solid fa-list mr-2 text-pink-500"></i> Katalog Produk Anda</h3>
                    <a href="{{ route('owner.products.create') }}" class="btn-a11y-admin bg-pink-500 text-white px-5 lg:px-6 py-2 lg:py-3 rounded-full text-xs lg:text-sm font-bold hover:bg-pink-600 shadow-md transition-colors flex items-center whitespace-nowrap">
                        <i class="fa-solid fa-circle-plus mr-2"></i> Tambah Bunga Baru
                    </a>
                </div>
                
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider whitespace-nowrap">Detail Produk</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider whitespace-nowrap">Harga</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider whitespace-nowrap">Kategori</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-center text-xs font-extrabold text-gray-600 uppercase tracking-wider whitespace-nowrap">Visual</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-right text-xs font-extrabold text-gray-600 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-pink-50/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-14 w-14 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 shadow-sm">
                                                @if($product->image_path)
                                                    <img class="h-14 w-14 object-cover" src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('images/' . $product->image_path) }}" alt="">
                                                @else
                                                    <div class="h-14 w-14 flex items-center justify-center text-gray-400"><i class="fa-solid fa-image"></i></div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-extrabold text-gray-900 text-a11y-admin">{{ $product->name }}</div>
                                                <div class="text-xs text-green-600 font-bold mt-1"><i class="fa-brands fa-whatsapp"></i> {{ $product->leads ? $product->leads->count() : 0 }} Leads</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-extrabold text-gray-800 text-a11y-admin">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-purple-100 text-purple-700 border border-purple-200 text-a11y-admin">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form action="{{ route('owner.products.toggle', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-a11y-admin px-4 py-2 inline-flex text-xs font-bold rounded-lg shadow-sm transition-transform hover:scale-105 text-white {{ $product->is_active ? 'bg-[#926a7a] hover:bg-[#7c4959]' : 'bg-[#7c4959] hover:bg-[#5d3642]' }}">
                                                @if($product->is_active)
                                                    <i class="fa-solid fa-eye mr-1"></i> Ditampilkan
                                                @else
                                                    <i class="fa-solid fa-eye-slash mr-1"></i> Disembunyikan
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('owner.products.edit', $product->id) }}" class="btn-a11y-admin flex items-center justify-center px-3 lg:px-4 py-2 text-xs font-bold text-white bg-[#ac9a9c] rounded-lg hover:bg-[#926a7a] transition-colors shadow-sm">
                                                <i class="fa-solid fa-pen-to-square lg:mr-1"></i> <span class="hidden lg:inline">Edit</span>
                                            </a>
                                            <form action="{{ route('owner.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Yakin ingin menghapus produk ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-a11y-admin flex items-center justify-center px-3 lg:px-4 py-2 text-xs font-bold text-white bg-[#7c4959] rounded-lg hover:bg-[#5d3642] transition-colors shadow-sm">
                                                    <i class="fa-solid fa-trash lg:mr-1"></i> <span class="hidden lg:inline">Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                                            <p class="text-gray-500 font-medium">Etalase toko Anda masih kosong.</p>
                                            <p class="text-sm text-gray-400 mt-1">Mulai tambahkan produk pertama Anda agar pembeli bisa melakukan pesanan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </main>
</body>
</html>

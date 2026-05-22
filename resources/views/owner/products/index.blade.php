<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-[#7c4959] leading-tight">
            <i class="fa-solid fa-store mr-2"></i> {{ __('Dasbor Manajemen Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-[#7c4959]">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-wider"><i class="fa-solid fa-box mr-1"></i> Total Produk Aktif</div>
                    <div class="mt-2 text-4xl font-extrabold text-[#7c4959]">{{ $products->where('is_active', true)->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-[#926a7a]">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-wider"><i class="fa-brands fa-whatsapp text-green-500 mr-1"></i> Total Klik WA (Leads)</div>
                    @php
                        // Calculate total leads safely
                        $totalLeads = $products->sum(function($prod) { return $prod->leads ? $prod->leads->count() : 0; });
                    @endphp
                    <div class="mt-2 text-4xl font-extrabold text-[#926a7a]">{{ $totalLeads }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-[#d4ccc0]">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-wider"><i class="fa-solid fa-shield-halved mr-1"></i> Status Toko</div>
                    <div class="mt-2 text-2xl font-bold uppercase {{ $shop->status == 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $shop->status }}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[#d4ccc0]/50">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-bold text-gray-800">Katalog Produk Anda</h3>
                    <a href="{{ route('owner.products.create') }}" class="bg-[#7c4959] text-white px-5 py-2.5 rounded-md text-sm font-bold hover:bg-[#5d3642] shadow-sm transition-colors flex items-center">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Bunga Baru
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Produk</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status Visual</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-md overflow-hidden bg-gray-100 border border-gray-200">
                                                @if($product->image_path)
                                                    <img class="h-12 w-12 object-cover" src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}" alt="">
                                                @else
                                                    <div class="h-12 w-12 flex items-center justify-center"><i class="fa-solid fa-image text-gray-400"></i></div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                                                <div class="text-xs text-green-600 font-semibold mt-0.5"><i class="fa-brands fa-whatsapp"></i> {{ $product->leads ? $product->leads->count() : 0 }} Interaksi WA</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#d4ccc0]/30 text-[#7c4959]">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form action="{{ route('owner.products.toggle', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm transition-transform hover:scale-105 {{ $product->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                                {{ $product->is_active ? '✅ Ditampilkan' : '❌ Disembunyikan' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('owner.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-md transition-colors" title="Edit Data"><i class="fa-solid fa-pen"></i></a>
                                            <form action="{{ route('owner.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Tindakan ini permanen. Yakin ingin menghapus bunga ini dari katalog Anda?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-md transition-colors" title="Hapus Permanen"><i class="fa-solid fa-trash"></i></button>
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
</x-app-layout>

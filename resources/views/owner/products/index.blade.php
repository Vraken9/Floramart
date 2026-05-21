<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Katalog Bunga: ') }} {{ $shop->name }}
            </h2>
            <a href="{{ route('owner.products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Tambah Bunga Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bunga</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                @if(str_starts_with($product->image_path, 'http'))
                                                    <img class="h-12 w-12 rounded-md object-cover border" src="{{ $product->image_path }}" alt="{{ $product->name }}">
                                                @else
                                                    <img class="h-12 w-12 rounded-md object-cover border" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-bold text-gray-900">{{ $product->name }}</div>
                                                <div class="text-xs text-gray-400">Slug: {{ $product->slug }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form action="{{ route('owner.products.toggle', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="cursor-pointer" title="Klik untuk mengubah status stok">
                                                @if($product->is_active)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-200">Tersedia (Aktif)</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 hover:bg-red-200">Kosong (Sembunyi)</span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-3">
                                            <a href="{{ route('owner.products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 py-1 rounded">Edit</a>

                                            <form action="{{ route('owner.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bunga ini dari etalase? Tindakan ini permanen.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada bunga di etalase Anda. Klik tombol "Tambah Bunga Baru" di atas.
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

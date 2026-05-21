<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Owner Florist Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">Selamat Datang di Dasbor Toko Anda!</h3>
                    <p class="text-gray-600 mb-6">Kelola katalog bunga Anda dan pantau performa toko di sini.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('owner.products.index') }}" class="block p-6 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition duration-150">
                            <h4 class="text-green-900 font-bold text-xl mb-1">Katalog Bunga</h4>
                            <p class="text-green-700 text-sm">Kelola etalase, tambah produk, dan perbarui harga.</p>
                        </a>

                        <div class="block p-6 bg-blue-50 border border-blue-200 rounded-lg opacity-75 cursor-not-allowed">
                            <h4 class="text-blue-900 font-bold text-xl mb-1">Analitik Klik WA</h4>
                            <p class="text-blue-700 text-sm">Lihat statistik pengunjung (Segera Hadir).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

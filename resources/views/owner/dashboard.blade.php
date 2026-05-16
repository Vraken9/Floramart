<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Owner Florist Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg border border-green-200">
                <div class="p-6 text-green-900 font-medium">
                    Selamat datang di Dasbor Toko Anda! Kelola katalog bunga Anda dan pantau statistik klik pelanggan di sini.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
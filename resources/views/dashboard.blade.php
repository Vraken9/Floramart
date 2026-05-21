<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Informasi:</strong>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Selamat datang! Anda berhasil login sebagai pengguna biasa.") }}
                    <br><br>
                    <a href="{{ route('shop.create') }}" class="text-indigo-600 hover:text-indigo-900 underline font-semibold">
                        + Klik di sini jika Anda ingin mendaftar untuk membuka Toko Bunga
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

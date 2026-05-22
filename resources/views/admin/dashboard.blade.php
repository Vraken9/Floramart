<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - FloraMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans flex flex-col min-h-screen">
    <x-navigation />

    <main class="flex-grow py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="font-bold text-2xl text-[#7c4959] leading-tight flex items-center">
                    <i class="fa-solid fa-shield-halved mr-3"></i> {{ __('Admin Control Panel - Persetujuan Toko') }}
                </h2>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-[#d4ccc0]/30 border border-[#7c4959]/30 text-[#7c4959] px-4 py-3 rounded-lg relative flex items-center shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                    <div>
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-[#d4ccc0]/50">
                <div class="p-6 text-gray-900 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-[#7c4959]">Daftar Pengajuan Toko Baru</h3>
                        <p class="text-sm text-gray-500 mt-1">Toko di bawah ini menunggu persetujuan Anda untuk mulai berjualan.</p>
                    </div>
                </div>

                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Info Toko & Lokasi</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pemilik / Kontak</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alasan Mendaftar</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pendingShops as $shop)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-gray-900 text-base mb-1">{{ $shop->name }}</div>
                                        <div class="text-xs text-gray-500 mb-2">
                                            <i class="fa-solid fa-location-dot mr-1 text-[#7c4959]"></i> 
                                            {{ $shop->district->name ?? 'Kecamatan N/A' }}, {{ $shop->district->regency->name ?? 'Kabupaten N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-600 border-l-2 border-[#d4ccc0] pl-2">{{ $shop->address_detail }}</div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-bold flex items-center">
                                            <i class="fa-solid fa-user mr-2 text-gray-400"></i> {{ $shop->user->name }}
                                        </div>
                                        <div class="text-sm text-green-600 font-semibold mt-1 flex items-center">
                                            <i class="fa-brands fa-whatsapp mr-2"></i> {{ $shop->whatsapp_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-md border border-gray-100 line-clamp-3">
                                            "{{ $shop->reason }}"
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('admin.shops.approve', $shop->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui toko ini? Akun pendaftar akan menjadi Owner.');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md font-bold text-xs text-white bg-[#7c4959] hover:bg-[#5d3642] shadow-sm transition-colors uppercase tracking-widest">
                                                <i class="fa-solid fa-check mr-2"></i> Setujui
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-solid fa-clipboard-check text-4xl text-[#d4ccc0] mb-3"></i>
                                            <p class="text-gray-500 font-medium">Tidak ada pengajuan toko baru saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

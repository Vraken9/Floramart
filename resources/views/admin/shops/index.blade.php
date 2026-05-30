<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Toko - FloraMart Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <x-navigation />

    <div class="flex flex-col lg:flex-row max-w-7xl mx-auto px-4 py-4 lg:py-8 gap-6">
        <x-admin-sidebar active="toko" />

        <main class="flex-grow space-y-6 lg:space-y-8 min-w-0">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                <div class="p-4 lg:p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50">
                    <h3 class="text-base lg:text-lg font-extrabold text-gray-800"><i class="fa-solid fa-store mr-2 text-green-600"></i> Katalog Toko</h3>
                </div>

            @if(session('success'))
                <div class="p-4 m-4 lg:mx-6 lg:mt-6 mb-0 bg-green-50 border border-green-200 text-green-700 rounded-lg font-semibold text-sm">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 m-4 lg:mx-6 lg:mt-6 mb-0 bg-red-50 border border-red-200 text-red-700 rounded-lg font-semibold text-sm">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                </div>
            @endif

                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider whitespace-nowrap">Info Toko</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider whitespace-nowrap">Pemilik</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th scope="col" class="px-4 lg:px-6 py-3 lg:py-4 text-right text-xs font-extrabold text-gray-600 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($shops as $shop)
                                <tr class="hover:bg-green-50/30 transition-colors">
                                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                                        <p class="font-extrabold text-sm text-gray-900 text-a11y-admin">{{ $shop->name }}</p>
                                        <p class="text-xs font-bold text-gray-500 mt-1"><i class="fa-brands fa-whatsapp text-green-500"></i> WA: {{ $shop->whatsapp_number }}</p>
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                                        <p class="text-sm font-extrabold text-gray-800 text-a11y-admin">{{ $shop->user->name }}</p>
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-bold uppercase tracking-widest rounded-full border text-a11y-admin 
                                            {{ $shop->status === 'approved' ? 'bg-green-100 text-green-700 border-green-200' : 
                                              ($shop->status === 'suspended' ? 'bg-orange-100 text-orange-700 border-orange-200' : 
                                              'bg-yellow-100 text-yellow-700 border-yellow-200') }}">
                                            {{ $shop->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 lg:py-4 text-right whitespace-nowrap">
                                        @if(!in_array($shop->status, ['pending', 'rejected']))
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.shops.edit', $shop->id) }}" class="btn-a11y-admin px-3 lg:px-4 py-2 inline-flex text-xs font-bold rounded-lg transition-transform hover:scale-105 bg-[#ac9a9c] text-white hover:bg-[#926a7a] shadow-sm">
                                                    <i class="fa-solid fa-pen-to-square lg:mr-1"></i> <span class="hidden lg:inline">Edit</span>
                                                </a>
                                                
                                                @if($shop->status !== 'approved')
                                                <form action="{{ route('admin.shops.update-status', $shop->id) }}" method="POST" class="inline-block">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" onclick="return confirm('Aktifkan kembali toko ini?')" class="btn-a11y-admin px-3 lg:px-4 py-2 inline-flex text-xs font-bold rounded-lg transition-transform hover:scale-105 bg-[#926a7a] text-white hover:bg-[#7c4959] shadow-sm">
                                                        <i class="fa-solid fa-check lg:mr-1"></i> <span class="hidden lg:inline">Aktifkan</span>
                                                    </button>
                                                </form>
                                                @endif

                                                @if($shop->status !== 'suspended')
                                                <form action="{{ route('admin.shops.update-status', $shop->id) }}" method="POST" class="inline-block">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="suspended">
                                                    <button type="submit" onclick="return confirm('Suspend toko ini sementara?')" class="btn-a11y-admin px-3 lg:px-4 py-2 inline-flex text-xs font-bold rounded-lg transition-transform hover:scale-105 bg-[#ac9a9c] text-white hover:bg-[#926a7a] shadow-sm">
                                                        <i class="fa-solid fa-pause lg:mr-1"></i> <span class="hidden lg:inline">Suspend</span>
                                                    </button>
                                                </form>
                                                @endif

                                                @if($shop->status !== 'banned')
                                                <form action="{{ route('admin.shops.update-status', $shop->id) }}" method="POST" class="inline-block">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="banned">
                                                    <button type="submit" onclick="return confirm('Banned/Blokir permanen toko ini?')" class="btn-a11y-admin px-3 lg:px-4 py-2 inline-flex text-xs font-bold rounded-lg transition-transform hover:scale-105 bg-[#7c4959] text-white hover:bg-[#5d3642] shadow-sm">
                                                        <i class="fa-solid fa-ban lg:mr-1"></i> <span class="hidden lg:inline">Blokir</span>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

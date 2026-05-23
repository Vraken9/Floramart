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

    <div class="flex max-w-7xl mx-auto px-4 py-8 gap-6">
        <aside class="w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sticky top-24">
                <div class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-4 px-3">Menu Admin</div>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-chart-pie w-6"></i> Ringkasan
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="flex items-center px-3 py-2.5 bg-red-50 text-red-700 rounded-lg font-bold">
                        <i class="fa-solid fa-store w-6"></i> Kelola Toko
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-box w-6"></i> Kelola Produk
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-chart-line w-6"></i> Analitik
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-grow space-y-6">
            <h1 class="text-2xl font-extrabold text-gray-900">Manajemen Toko</h1>

            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg font-semibold text-sm">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg font-semibold text-sm">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold border-b border-gray-200">Info Toko</th>
                                <th class="px-6 py-4 font-bold border-b border-gray-200">Pemilik</th>
                                <th class="px-6 py-4 font-bold border-b border-gray-200">Status</th>
                                <th class="px-6 py-4 font-bold border-b border-gray-200 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($shops as $shop)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">{{ $shop->name }}</p>
                                        <p class="text-xs text-gray-500"><i class="fa-brands fa-whatsapp text-green-500"></i> {{ $shop->whatsapp_number }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-700">{{ $shop->user->name }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border 
                                            {{ $shop->status === 'approved' ? 'bg-green-100 text-green-700 border-green-200' : 
                                              ($shop->status === 'suspended' ? 'bg-red-100 text-red-700 border-red-200' : 
                                              'bg-yellow-100 text-yellow-700 border-yellow-200') }}">
                                            {{ $shop->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($shop->status !== 'pending')
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.shops.edit', $shop->id) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg border bg-white text-blue-600 border-blue-200 hover:bg-blue-50 transition-colors">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.shops.toggle', $shop->id) }}" method="POST" class="inline-block">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" onclick="return confirm('Yakin ingin mengubah status toko ini?')" 
                                                        class="px-3 py-1.5 text-xs font-bold rounded-lg border transition-colors 
                                                        {{ $shop->status === 'approved' ? 'bg-white text-red-600 border-red-200 hover:bg-red-50' : 'bg-white text-green-600 border-green-200 hover:bg-green-50' }}">
                                                        <i class="{{ $shop->status === 'approved' ? 'fa-solid fa-ban' : 'fa-solid fa-check-circle' }}"></i> {{ $shop->status === 'approved' ? 'Suspend' : 'Aktifkan' }}
                                                    </button>
                                                </form>
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

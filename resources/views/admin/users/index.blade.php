<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - FloraMart Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <x-navigation />

    <div class="flex max-w-7xl mx-auto px-4 py-8 gap-6">
        <aside class="w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sticky top-24">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 px-3">Navigasi Utama</div>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Ringkasan
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Kelola Toko
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Kelola Produk
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 bg-gray-50 text-[#7c4959] rounded-lg font-medium text-sm">
                        Kelola Pengguna
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium text-sm transition-colors">
                        Analitik
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-grow space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="border-b border-gray-200 pb-4">
                <h1 class="text-2xl font-light text-gray-900 tracking-tight">Manajemen <span class="font-bold text-[#7c4959]">Pengguna</span></h1>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50">
                    <h3 class="text-lg font-extrabold text-gray-800"><i class="fa-solid fa-users mr-2 text-indigo-500"></i> Katalog Pengguna</h3>
                </div>
                
                <div class="overflow-x-auto p-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-100 rounded-lg">
                                <th scope="col" class="px-6 py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider rounded-tl-lg rounded-bl-lg">Info Pengguna</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-extrabold text-gray-600 uppercase tracking-wider">Peran (Role)</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-extrabold text-gray-600 uppercase tracking-wider rounded-tr-lg rounded-br-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-extrabold text-sm text-gray-900 text-a11y-admin">{{ $user->name }}</p>
                                        <p class="text-xs font-bold text-gray-500 mt-1"><i class="fa-solid fa-envelope text-indigo-400"></i> {{ $user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-bold uppercase tracking-widest rounded-full border text-a11y-admin
                                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-700 border-red-200' : 
                                              ($user->role === 'owner' ? 'bg-purple-100 text-purple-700 border-purple-200' : 
                                              'bg-blue-100 text-blue-700 border-blue-200') }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            @if($user->id !== auth()->id())
                                                @if($user->role === 'owner')
                                                <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" class="inline-block">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="role" value="user">
                                                    <button type="submit" onclick="return confirm('Turunkan role pengguna ini menjadi User biasa?')" class="btn-a11y-admin flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-[#ac9a9c] rounded-lg hover:bg-[#926a7a] transition-colors shadow-sm">
                                                        <i class="fa-solid fa-arrow-down mr-1"></i> Turunkan
                                                    </button>
                                                </form>
                                                @elseif($user->role === 'user')
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus permanen user ini beserta tokonya (jika ada)?')" class="btn-a11y-admin flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-[#7c4959] rounded-lg hover:bg-[#5d3642] transition-colors shadow-sm">
                                                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                    </button>
                                                </form>
                                                @endif
                                            @endif
                                        </div>
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

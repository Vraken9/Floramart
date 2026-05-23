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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sticky top-24">
                <div class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-4 px-3">Menu Admin</div>
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-chart-pie w-6"></i> Ringkasan
                    </a>
                    <a href="{{ route('admin.shops.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-store w-6"></i> Kelola Toko
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-box w-6"></i> Kelola Produk
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 bg-red-50 text-red-700 rounded-lg font-bold">
                        <i class="fa-solid fa-users w-6"></i> Kelola User
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-lg font-semibold transition-colors">
                        <i class="fa-solid fa-chart-line w-6"></i> Analitik
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

            <h1 class="text-2xl font-extrabold text-gray-900">Manajemen Pengguna</h1>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-bold text-gray-800">Daftar Semua Pengguna</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold border-b border-gray-200">Nama</th>
                                <th class="px-6 py-4 font-bold border-b border-gray-200">Email</th>
                                <th class="px-6 py-4 font-bold border-b border-gray-200">Terdaftar Sejak</th>
                                <th class="px-6 py-4 font-bold border-b border-gray-200">Role & Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-700">{{ $user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border 
                                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-700 border-red-200' : 
                                                  ($user->role === 'owner' ? 'bg-blue-100 text-blue-700 border-blue-200' : 
                                                  'bg-gray-100 text-gray-700 border-gray-200') }}">
                                                {{ $user->role }}
                                            </span>

                                            @if($user->id !== auth()->id())
                                                @if($user->role === 'owner')
                                                    <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="role" value="user">
                                                        <button type="submit" onclick="return confirm('Turunkan role pengguna ini menjadi User biasa?')" class="px-3 py-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border border-yellow-200 text-xs font-bold rounded transition-colors">
                                                            <i class="fa-solid fa-arrow-down"></i> Turunkan
                                                        </button>
                                                    </form>
                                                @elseif($user->role === 'user')
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus akun pengguna ini secara permanen?')" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-bold rounded transition-colors">
                                                            <i class="fa-solid fa-trash"></i> Hapus
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

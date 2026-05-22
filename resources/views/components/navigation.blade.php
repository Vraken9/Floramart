<nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-extrabold text-[#7c4959] flex items-center gap-2">
                    Flora<span class="text-[#926a7a]">Mart</span>
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#7c4959] border-b-2 border-[#7c4959]' : 'text-gray-500 hover:text-[#7c4959]' }} font-semibold transition">Beranda</a>
                <a href="{{ route('katalog.index') }}" class="{{ request()->routeIs('katalog.index') ? 'text-[#7c4959] border-b-2 border-[#7c4959]' : 'text-gray-500 hover:text-[#7c4959]' }} font-semibold transition">Katalog</a>
                <a href="{{ route('shops.index') }}" class="{{ request()->routeIs('shops.index') ? 'text-[#7c4959] border-b-2 border-[#7c4959]' : 'text-gray-500 hover:text-[#7c4959]' }} font-semibold transition">Toko</a>
                @if (Auth::check())
                    <div class="relative pl-4 border-l border-gray-300" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-[#7c4959] text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-semibold text-gray-700 hidden md:block">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-gauge mr-2 text-[#7c4959]"></i> Dasbor Utama</a>
                            @if(Auth::user()->role === 'owner')
                                <a href="{{ route('owner.products.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-store mr-2 text-[#7c4959]"></i> Dasbor Toko</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-user-gear mr-2 text-[#7c4959]"></i> Kelola Profil</a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#7c4959] font-semibold border-l pl-4 border-gray-300">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-[#7c4959] hover:bg-[#5d3642] text-white px-4 py-2 rounded-md text-xs font-semibold uppercase shadow-sm">Daftar</a>
                @endif
            </div>
        </div>
    </div>
</nav>

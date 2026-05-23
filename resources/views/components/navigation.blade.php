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
                
                @if(!Auth::check() || Auth::user()->role !== 'admin')
                    @php
                        $wishlistCount = 0;
                        if(Auth::check()) {
                            $wishlistCount = Auth::user()->favoriteProducts()->count();
                        }
                    @endphp
                    @if(Auth::check())
                        <a href="{{ route('wishlist.index') }}" class="relative text-gray-500 hover:text-[#7c4959] transition flex items-center h-full mr-2" title="Favorit Saya">
                            <i class="fa-solid fa-heart text-xl"></i>
                            @if($wishlistCount > 0)
                                <span class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 bg-red-500 text-white rounded-full text-[10px] w-4 h-4 flex items-center justify-center font-bold">
                                    {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                                </span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="relative text-gray-500 hover:text-[#7c4959] transition flex items-center h-full mr-2" title="Login untuk melihat favorit">
                            <i class="fa-regular fa-heart text-xl"></i>
                        </a>
                    @endif
                @endif

                @if (Auth::check())
                    <div class="relative pl-4 border-l border-gray-300" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 focus:outline-none">
                            <div class="w-8 h-8 rounded-full bg-[#7c4959] text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-semibold text-gray-700 hidden md:block">{{ Auth::user()->name }}</span>
            <span class="hidden md:inline-block ml-2 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full border 
                {{ Auth::user()->role === 'admin' ? 'bg-red-100 text-red-700 border-red-200' : 
                  (Auth::user()->role === 'owner' ? 'bg-blue-100 text-blue-700 border-blue-200' : 
                  'bg-gray-100 text-gray-700 border-gray-200') }}">
                {{ Auth::user()->role }}
            </span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-gauge mr-2 text-[#7c4959]"></i> Dasbor Utama</a>
                            @if(Auth::user()->role === 'owner')
                                <a href="{{ route('owner.products.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-store mr-2 text-[#7c4959]"></i> Dasbor Toko</a>
                                <a href="{{ route('owner.shop.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-shop-lock mr-2 text-[#7c4959]"></i> Pengaturan Toko</a>
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

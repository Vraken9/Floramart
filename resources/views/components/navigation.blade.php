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
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-[#7c4959] font-semibold border-l pl-4 border-gray-300">Dasbor</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#7c4959] font-semibold border-l pl-4 border-gray-300">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-[#7c4959] hover:bg-[#5d3642] text-white px-4 py-2 rounded-md text-xs font-semibold uppercase shadow-sm">Daftar</a>
                @endif
            </div>
        </div>
    </div>
</nav>

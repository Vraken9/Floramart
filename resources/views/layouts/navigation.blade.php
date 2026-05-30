<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-extrabold text-[#7c4959] tracking-tight">Flora<span class="text-[#926a7a]">Mart</span></a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-[#7c4959] text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-[#7c4959] hover:border-[#d4ccc0]' }} text-sm transition-colors duration-150 ease-in-out">
                        Beranda
                    </a>
                    <a href="{{ route('katalog.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('katalog.index') ? 'border-[#7c4959] text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-[#7c4959] hover:border-[#d4ccc0]' }} text-sm transition-colors duration-150 ease-in-out">
                        Katalog Bunga
                    </a>
                    <a href="{{ route('shops.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('shops.index') ? 'border-[#7c4959] text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-[#7c4959] hover:border-[#d4ccc0]' }} text-sm transition-colors duration-150 ease-in-out">
                        Toko Florist
                    </a>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-[#7c4959] text-gray-900 font-bold' : 'border-transparent text-gray-500 hover:text-[#7c4959] hover:border-[#d4ccc0]' }} text-sm transition-colors duration-150 ease-in-out">
                        Favorit Saya
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <!-- Accessibility Toggle -->
                <div class="me-4 flex items-center">
                    <button onclick="toggleAccessibilityMode()" class="text-gray-500 hover:text-[#7c4959] focus:outline-none flex items-center gap-1.5" title="Toggle Mode Aksesibilitas">
                        <i id="a11yModeIconDashboard" class="fa-solid fa-universal-access"></i>
                        <span class="text-xs font-semibold hidden md:inline">Aksesibilitas</span>
                    </button>
                </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <a href="{{ route('login') }}" class="btn-a11y-auth-login text-sm text-gray-700 underline">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-a11y-auth-register ml-4 text-sm text-gray-700 underline">Register</a>
                @endif
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden shadow-lg border-b border-[#d4ccc0]/50 absolute w-full bg-white z-50">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 {{ request()->routeIs('home') ? 'border-[#7c4959] text-[#7c4959] bg-[#d4ccc0]/10 font-bold' : 'border-transparent text-gray-500 hover:text-[#7c4959] hover:bg-gray-50 hover:border-[#d4ccc0]' }} text-start text-base transition duration-150 ease-in-out">
                Beranda
            </a>
            <a href="{{ route('katalog.index') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 {{ request()->routeIs('katalog.index') ? 'border-[#7c4959] text-[#7c4959] bg-[#d4ccc0]/10 font-bold' : 'border-transparent text-gray-500 hover:text-[#7c4959] hover:bg-gray-50 hover:border-[#d4ccc0]' }} text-start text-base transition duration-150 ease-in-out">
                Katalog Bunga
            </a>
            <a href="{{ route('shops.index') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 {{ request()->routeIs('shops.index') ? 'border-[#7c4959] text-[#7c4959] bg-[#d4ccc0]/10 font-bold' : 'border-transparent text-gray-500 hover:text-[#7c4959] hover:bg-gray-50 hover:border-[#d4ccc0]' }} text-start text-base transition duration-150 ease-in-out">
                Toko Florist
            </a>
            <a href="{{ route('dashboard') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-[#7c4959] text-[#7c4959] bg-[#d4ccc0]/10 font-bold' : 'border-transparent text-gray-500 hover:text-[#7c4959] hover:bg-gray-50 hover:border-[#d4ccc0]' }} text-start text-base transition duration-150 ease-in-out">
                Favorit Saya
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="px-4 pb-2">
                <a href="{{ route('login') }}" class="btn-a11y-auth-login text-sm font-medium text-gray-700 hover:text-gray-900">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-a11y-auth-register ml-4 text-sm font-medium text-gray-700 hover:text-gray-900">Register</a>
                @endif
            </div>
            @endauth
        </div>
    </div>
</nav>

<x-accessibility-filters />
<x-accessibility-widget />

@props(['active' => ''])

<style>[x-cloak] { display: none !important; }</style>

{{-- Mobile Sidebar Toggle Button --}}
<div class="lg:hidden sticky top-[57px] z-30 bg-white border-b border-gray-100 shadow-sm px-4 py-2.5">
    <button onclick="document.getElementById('admin-sidebar').classList.toggle('-translate-x-full')" class="flex items-center gap-2 text-sm font-bold text-gray-700 hover:text-[#7c4959]">
        <i class="fa-solid fa-bars text-lg"></i> Menu Dashboard
    </button>
</div>

{{-- Sidebar Overlay (mobile) --}}
<div id="admin-sidebar-overlay" class="fixed inset-0 bg-black/30 z-40 lg:hidden hidden" onclick="document.getElementById('admin-sidebar').classList.add('-translate-x-full'); this.classList.add('hidden')"></div>

{{-- Sidebar --}}
<aside id="admin-sidebar" class="fixed top-0 left-0 h-full w-64 bg-white z-50 shadow-lg border-r border-gray-100 transform -translate-x-full transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 lg:shadow-none lg:border-0 lg:w-64 flex-shrink-0">
    <div class="p-4 sticky top-24 lg:bg-white lg:rounded-xl lg:shadow-sm lg:border lg:border-gray-100">
        {{-- Mobile close button --}}
        <div class="flex items-center justify-between mb-4 lg:hidden">
            <span class="text-sm font-extrabold text-[#7c4959]">FloraMart Admin</span>
            <button onclick="document.getElementById('admin-sidebar').classList.add('-translate-x-full'); document.getElementById('admin-sidebar-overlay').classList.add('hidden')" class="p-1 text-gray-500 hover:text-[#7c4959]">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 px-3">Navigasi Utama</div>
        <nav class="space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2.5 {{ $active === 'ringkasan' ? 'bg-gray-50 text-[#7c4959]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} rounded-lg font-medium text-sm transition-colors">
                <i class="fa-solid fa-gauge w-5 text-center"></i> Ringkasan
            </a>
            <a href="{{ route('admin.shops.index') }}" class="flex items-center gap-2 px-3 py-2.5 {{ $active === 'toko' ? 'bg-gray-50 text-[#7c4959]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} rounded-lg font-medium text-sm transition-colors">
                <i class="fa-solid fa-store w-5 text-center"></i> Kelola Toko
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 px-3 py-2.5 {{ $active === 'produk' ? 'bg-gray-50 text-[#7c4959]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} rounded-lg font-medium text-sm transition-colors">
                <i class="fa-solid fa-boxes-stacked w-5 text-center"></i> Kelola Produk
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-3 py-2.5 {{ $active === 'pengguna' ? 'bg-gray-50 text-[#7c4959]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} rounded-lg font-medium text-sm transition-colors">
                <i class="fa-solid fa-users w-5 text-center"></i> Kelola Pengguna
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-2 px-3 py-2.5 {{ $active === 'analitik' ? 'bg-gray-50 text-[#7c4959]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} rounded-lg font-medium text-sm transition-colors">
                <i class="fa-solid fa-chart-line w-5 text-center"></i> Analitik
            </a>
        </nav>
    </div>
</aside>

<script>
    // Toggle sidebar for mobile
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.querySelector('[onclick*="admin-sidebar"]');
        if(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('admin-sidebar-overlay').classList.toggle('hidden');
            });
        }
    });
</script>

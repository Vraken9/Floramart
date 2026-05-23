<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja - FloraMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased bg-[#fdfbf7] text-gray-900 font-sans flex flex-col min-h-screen">
    <x-navigation />

    <main class="flex-grow py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-bold text-3xl text-[#7c4959] leading-tight mb-8">
                <i class="fa-solid fa-cart-shopping mr-3"></i> Keranjang Belanja
            </h2>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg font-semibold flex items-center shadow-sm">
                    <i class="fa-solid fa-circle-check mr-2 text-xl"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg font-semibold flex items-center shadow-sm">
                    <i class="fa-solid fa-circle-xmark mr-2 text-xl"></i> {{ session('error') }}
                </div>
            @endif

            @if($cartItems->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-[#d4ccc0]/50 p-16 flex flex-col items-center justify-center text-center">
                    <div class="w-32 h-32 bg-[#d4ccc0]/20 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-cart-arrow-down text-5xl text-[#7c4959]/50"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Anda masih kosong</h3>
                    <p class="text-gray-500 mb-8 max-w-md">Belum ada bunga indah yang Anda pilih. Mari temukan rangkaian bunga yang sempurna untuk momen spesial Anda.</p>
                    <a href="{{ route('katalog.index') }}" class="inline-flex items-center px-6 py-3 bg-[#7c4959] border border-transparent rounded-full font-bold text-white hover:bg-[#5d3642] transition-colors shadow-md">
                        <i class="fa-solid fa-seedling mr-2"></i> Jelajahi Katalog
                    </a>
                </div>
            @else
                @php
                    $groupedItems = $cartItems->groupBy('product.shop_id');
                @endphp
                <div x-data="cart()">
                    <div class="space-y-8">
                        @foreach($groupedItems as $shopId => $items)
                            @php
                                $shop = $items->first()->product->shop;
                            @endphp
                            <div class="bg-white rounded-2xl shadow-sm border border-[#d4ccc0]/50 overflow-hidden">
                                <!-- Shop Header -->
                                <div class="bg-[#fdfbf7] px-6 py-4 border-b border-[#d4ccc0]/50 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-store text-[#7c4959] mr-3 text-xl"></i>
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900">{{ $shop->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $shop->district->name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shop Items -->
                                <div class="p-6 space-y-6">
                                    @foreach($items as $item)
                                        <div class="flex flex-col sm:flex-row gap-6 pb-6 {{ !$loop->last ? 'border-b border-gray-100' : '' }}" 
                                             id="item-{{ $item->id }}">
                                            
                                            <!-- Product Image -->
                                            <div class="sm:w-32 h-32 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                                <img src="{{ str_starts_with($item->product->image_path, 'http') ? $item->product->image_path : asset('storage/' . $item->product->image_path) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                            
                                            <!-- Product Details -->
                                            <div class="flex-grow flex flex-col justify-between">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="text-lg font-bold text-gray-900 mb-1">
                                                            <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-[#7c4959] transition-colors">
                                                                {{ $item->product->name }}
                                                            </a>
                                                        </h4>
                                                        <div class="font-extrabold text-[#7c4959]">
                                                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1" title="Hapus dari keranjang" onclick="return confirm('Hapus item ini?')">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                
                                                <div class="flex items-center justify-between mt-4">
                                                    <div class="text-sm font-medium text-gray-500">
                                                        Subtotal: <span class="text-gray-900 font-bold" x-text="'Rp ' + formatRupiah(getItemSubtotal({{ $item->id }}))"></span>
                                                    </div>
                                                    
                                                    <!-- Quantity Control -->
                                                    <div class="flex items-center border border-[#d4ccc0] rounded-lg overflow-hidden h-9">
                                                        <button @click="updateQuantity({{ $item->id }}, -1)" class="w-9 h-full bg-gray-50 hover:bg-[#d4ccc0]/30 text-gray-600 flex items-center justify-center transition-colors">
                                                            <i class="fa-solid fa-minus text-[10px]"></i>
                                                        </button>
                                                        <div class="w-10 h-full flex items-center justify-center font-bold text-sm text-gray-800 bg-white border-x border-[#d4ccc0]" id="qty-{{ $item->id }}">
                                                            {{ $item->quantity }}
                                                        </div>
                                                        <button @click="updateQuantity({{ $item->id }}, 1)" class="w-9 h-full bg-gray-50 hover:bg-[#d4ccc0]/30 text-gray-600 flex items-center justify-center transition-colors">
                                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Shop Checkout Footer -->
                                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Total belanja dari <span class="font-semibold text-gray-700">{{ $shop->name }}</span></p>
                                        <p class="text-xl font-extrabold text-[#7c4959]" x-text="'Rp ' + formatRupiah(getShopTotal({{ $shopId }}))"></p>
                                    </div>
                                    <a href="{{ route('cart.checkout', $shopId) }}" target="_blank" onclick="setTimeout(() => window.location.reload(), 2000)" class="px-6 py-3 bg-[#7c4959] hover:bg-[#5d3642] text-white rounded-xl font-bold shadow-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                        <i class="fa-brands fa-whatsapp text-lg"></i> Checkout Toko Ini
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Alpine.js Logic -->
                <script>
                    function cart() {
                        return {
                            items: [
                                @foreach($cartItems as $item)
                                {
                                    id: {{ $item->id }},
                                    shop_id: {{ $item->product->shop_id }},
                                    price: {{ $item->product->price }},
                                    quantity: {{ $item->quantity }}
                                },
                                @endforeach
                            ],
                            
                            formatRupiah(angka) {
                                return new Intl.NumberFormat('id-ID').format(angka);
                            },

                            getItemSubtotal(id) {
                                const item = this.items.find(i => i.id === id);
                                return item ? item.price * item.quantity : 0;
                            },
                            
                            getShopTotal(shopId) {
                                return this.items
                                    .filter(item => item.shop_id === shopId)
                                    .reduce((sum, item) => sum + (item.price * item.quantity), 0);
                            },
                            
                            updateQuantity(id, change) {
                                const item = this.items.find(i => i.id === id);
                                if (!item) return;
                                
                                const newQty = item.quantity + change;
                                if (newQty < 1) return; // Prevent going below 1
                                
                                item.quantity = newQty;
                                document.getElementById('qty-' + id).innerText = newQty;
                                
                                // Send update to server (Fetch API)
                                fetch(`/cart/update/${id}`, {
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ quantity: newQty })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if(!data.success) {
                                        console.error('Failed to update server quantity');
                                    }
                                })
                                .catch(err => console.error(err));
                            }
                        }
                    }
                </script>
            @endif
        </div>
    </main>
</body>
</html>

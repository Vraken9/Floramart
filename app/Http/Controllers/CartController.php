<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = session()->getId();
            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }

    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('product.shop')->get();

        return view('cart.index', compact('cartItems', 'cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();
        $product = Product::findOrFail($request->product_id);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($id);
        
        // Ensure the item belongs to the current cart
        $cart = $this->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['success' => true, 'message' => 'Kuantitas diperbarui']);
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // Ensure the item belongs to the current cart
        $cart = $this->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function checkout($shopId)
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->whereHas('product', function($q) use ($shopId) {
            $q->where('shop_id', $shopId);
        })->with('product.shop')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada item untuk toko ini di keranjang.');
        }

        $shop = $cartItems->first()->product->shop;
        
        // 1. Format Nomor WhatsApp
        $waNumber = $shop->whatsapp_number;
        if(str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }

        // 2. Susun Pesan
        $pesan = "Halo *{$shop->name}*,\nSaya ingin memesan produk berikut dari FloraMart:\n\n";
        
        $total = 0;
        foreach ($cartItems as $item) {
            $subtotal = $item->quantity * $item->product->price;
            $total += $subtotal;
            $pesan .= "- {$item->product->name} (x{$item->quantity}) : Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }
        
        $pesan .= "\n*Total Belanja: Rp " . number_format($total, 0, ',', '.') . "*\n\nMohon konfirmasi ketersediaan dan informasi pembayarannya. Terima kasih!";

        // 3. Hapus item dari keranjang setelah di-checkout
        $cart->items()->whereIn('id', $cartItems->pluck('id'))->delete();

        // 4. Redirect ke WhatsApp
        $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($pesan);
        
        return redirect()->away($waUrl);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $favoriteProducts = \Illuminate\Support\Facades\Auth::user()
            ->favoriteProducts()
            ->with(['category', 'shop.district.regency'])
            ->latest()
            ->get();
            
        $categories = \App\Models\Category::all();

        return view('dashboard', compact('favoriteProducts', 'categories'));
    }

    /**
     * Fungsi tunggal untuk menambah atau menghapus produk dari wishlist.
     */
    public function toggle($productId)
    {
        // 1. Pastikan produk yang diklik memang ada di database
        $product = Product::findOrFail($productId);

        // 2. Ambil data user yang sedang login saat ini
        $user = Auth::user();

        // 3. Sihir Laravel: otomatis pasang (attach) jika belum ada, atau lepas (detach) jika sudah ada
        $status = $user->favoriteProducts()->toggle($product->id);

        // 4. Cek apakah aksenya tadi memfavoritkan atau membatalkan (untuk pesan notifikasi)
        if (count($status['attached']) > 0) {
            $message = 'Bunga berhasil ditambahkan ke daftar favorit Anda!';
        } else {
            $message = 'Bunga dihapus dari daftar favorit Anda.';
        }

        // Kembalikan ke halaman sebelumnya dengan membawa pesan
        return back()->with('success', $message);
    }
}

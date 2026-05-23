<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    // 1. Menampilkan daftar produk milik Toko si Owner
    public function index()
    {
        // Ambil data toko yang dimiliki oleh Owner yang sedang login
        $shop = Shop::where('user_id', Auth::id())->first();

        // Jika karena suatu hal toko tidak ditemukan, kembalikan ke dashboard
        if (!$shop) {
            return redirect()->route('dashboard')->with('status', 'Akses ditolak. Anda belum memiliki toko.');
        }

        // Jika toko sedang menunggu persetujuan
        if (in_array($shop->status, ['pending', 'in_review'])) {
            return view('owner.pending', compact('shop'));
        }

        // Jika toko disuspend atau dibanned
        if (in_array($shop->status, ['suspended', 'banned'])) {
            return view('owner.suspended', compact('shop'));
        }

        // Ambil semua produk yang terikat dengan ID toko ini
        $products = Product::with('category')
            ->where('shop_id', $shop->id)
            ->get();

        return view('owner.products.index', compact('products', 'shop'));
    }

    // 2. Menampilkan form tambah produk baru
    public function create()
    {
        $shop = Shop::where('user_id', Auth::id())->first();

        // Ambil semua kategori untuk pilihan dropdown di form (misal: Buket Wisuda, Bunga Papan)
        $categories = Category::all();

        return view('owner.products.create', compact('categories', 'shop'));
    }
    public function store(Request $request)
    {
        // 1. Validasi dinamis: Wajib isi salah satu (File ATAU URL)
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image_file' => 'required_without:image_url|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'required_without:image_file|nullable|url|max:2048',
        ]);

        $shop = Shop::where('user_id', Auth::id())->first();

        // 2. Logika Cerdas Penyimpanan Gambar
        $imagePath = null;
        if ($request->hasFile('image_file')) {
            // Jika memilih upload dari laptop
            $imagePath = $request->file('image_file')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            // Jika memilih menggunakan Link Eksternal / Cloud
            $imagePath = $request->image_url;
        }

        $slug = Str::slug($request->name) . '-' . time();

        Product::create([
            'shop_id' => $shop->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'is_active' => true,
        ]);

        return redirect()->route('owner.products.index')->with('success', 'Bunga baru berhasil ditambahkan ke etalase!');
    }
    // Jangan lupa pastikan di bagian paling atas file sudah ada:
    // use Illuminate\Support\Facades\Storage;

    // 4. Menampilkan halaman form edit produk
    public function edit($id)
    {
        $shop = Shop::where('user_id', Auth::id())->first();

        // Kunci pencarian: Produk harus milik toko owner yang sedang login
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();
        $categories = Category::all();

        return view('owner.products.edit', compact('product', 'categories', 'shop'));
    }

    // 5. Memproses pembaruan data produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'nullable|url|max:2048',
        ]);

        $shop = Shop::where('user_id', Auth::id())->first();
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        // Logika cerdas pembaruan gambar
        $imagePath = $product->image_path;

        if ($request->hasFile('image_file')) {
            // Hapus foto lama di lokal jika sebelumnya menggunakan metode upload file
            if ($product->image_path && !str_starts_with($product->image_path, 'http')) {
               Storage::disk('public')->delete($product->image_path);
            }
            $imagePath = $request->file('image_file')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('owner.products.index')->with('success', 'Data bunga berhasil diperbarui!');
    }

    // 6. Menghapus produk dari etalase
    public function destroy($id)
    {
        $shop = Shop::where('user_id', Auth::id())->first();
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        // Hapus file gambar dari penyimpanan lokal jika ada
        if ($product->image_path && !str_starts_with($product->image_path, 'http')) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('owner.products.index')->with('success', 'Bunga berhasil dihapus dari katalog.');
    }

    // 7. Fitur Cepat: Mengubah status ketersediaan (Tersedia/Kosong)
    public function toggleStatus($id)
    {
        $shop = Shop::where('user_id', Auth::id())->first();
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        $product->is_active = !$product->is_active;
        $product->save();

        return redirect()->route('owner.products.index')->with('success', 'Status ketersediaan bunga berhasil diubah!');
    }
}

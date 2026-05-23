<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;

class AdminController extends Controller
{
    public function approveShop($id)
    {
        // 1. Cari data toko berdasarkan ID
        $shop = Shop::findOrFail($id);

        // 2. Ubah status toko menjadi disetujui
        $shop->status = 'approved';
        $shop->save();

        // 3. Cari akun user yang memiliki toko tersebut
        $user = User::findOrFail($shop->user_id);

        // 4. Naikkan pangkat/role user tersebut menjadi 'owner'
        $user->role = 'owner';
        $user->save();

        // 5. Kembalikan ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Toko ' . $shop->name . ' berhasil disetujui! Akun pendaftar sekarang menjadi Owner.');
    }

    public function rejectShop($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->status = 'rejected';
        $shop->save();

        return redirect()->route('dashboard')->with('success', 'Pendaftaran Toko ' . $shop->name . ' telah ditolak.');
    }
    public function shopsIndex()
    {
        // Fetch all shops with their user and region data
        $shops = \App\Models\Shop::with(['user', 'district.regency'])->latest()->get();
        return view('admin.shops.index', compact('shops'));
    }

    public function toggleShopStatus(\Illuminate\Http\Request $request, $id)
    {
        $shop = \App\Models\Shop::findOrFail($id);
        
        // Toggle between approved and suspended
        if ($shop->status === 'approved') {
            $shop->update(['status' => 'suspended']);
            $message = "Toko {$shop->name} berhasil dinonaktifkan (Suspended).";
        } elseif ($shop->status === 'suspended') {
            $shop->update(['status' => 'approved']);
            $message = "Toko {$shop->name} berhasil diaktifkan kembali.";
        } else {
            return back()->with('error', 'Hanya toko approved/suspended yang bisa diubah statusnya melalui menu ini.');
        }

        return back()->with('success', $message);
    }

    public function editShop($id)
    {
        $shop = Shop::findOrFail($id);
        return view('admin.shops.edit', compact('shop'));
    }

    public function updateShop(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'address_detail' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $shop->update($request->only(['name', 'whatsapp_number', 'address_detail', 'description']));
        return redirect()->route('admin.shops.index')->with('success', 'Profil toko berhasil diperbarui!');
    }

    public function productsIndex()
    {
        $shops = Shop::whereIn('status', ['approved', 'suspended'])->with('products')->get();
        return view('admin.products.index', compact('shops'));
    }

    public function analyticsIndex()
    {
        $totalUsers = User::count();
        $totalShops = Shop::count();
        $totalProducts = \App\Models\Product::count();
        $totalWaClicks = \App\Models\ProductLead::count();

        // Data 7 hari terakhir
        $recentWaClicks = \App\Models\ProductLead::where('created_at', '>=', now()->subDays(7))->count();
        $recentRegistrations = User::where('created_at', '>=', now()->subDays(7))->count();

        return view('admin.analytics.index', compact(
            'totalUsers', 'totalShops', 'totalProducts', 
            'totalWaClicks', 'recentWaClicks', 'recentRegistrations'
        ));
    }

    public function editProduct($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'nullable|url|max:2048',
        ]);

        $imagePath = $product->image_path;

        if ($request->hasFile('image_file')) {
            if ($product->image_path && !str_starts_with($product->image_path, 'http')) {
               \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
            }
            $imagePath = $request->file('image_file')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Data produk berhasil diperbarui oleh Admin!');
    }
}

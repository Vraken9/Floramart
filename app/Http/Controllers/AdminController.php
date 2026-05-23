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

    public function rejectShop(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:500'
        ]);

        $shop = Shop::findOrFail($id);
        $shop->status = 'rejected';
        $shop->rejected_reason = $request->rejected_reason;
        $shop->save();

        return redirect()->route('dashboard')->with('success', 'Toko berhasil ditolak.');
    }

    public function shopsIndex()
    {
        // Fetch all shops with their user and region data
        $shops = \App\Models\Shop::with(['user', 'district.regency'])->latest()->get();
        return view('admin.shops.index', compact('shops'));
    }

    public function updateShopStatus(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,suspended,banned',
            'suspend_reason' => 'nullable|string|max:500'
        ]);

        $shop = \App\Models\Shop::findOrFail($id);
        $shop->status = $request->status;

        // Simpan alasan suspend/ban jika ada
        if (in_array($request->status, ['suspended', 'banned']) && $request->filled('suspend_reason')) {
            $shop->rejected_reason = $request->suspend_reason;
        }

        $shop->save();

        $message = "Status toko {$shop->name} berhasil diubah menjadi {$request->status}.";
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'whatsapp_number', 'address_detail', 'description']);

        if ($request->hasFile('logo')) {
            if ($shop->logo_path && !str_starts_with($shop->logo_path, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($shop->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('shop_logos', 'public');
        }

        $shop->update($data);
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
        $recentWaClicks = \App\Models\ProductLead::where('clicked_at', '>=', now()->subDays(7))->count();
        $recentRegistrations = User::where('created_at', '>=', now()->subDays(7))->count();
        
        $totalProfileViews = \App\Models\ShopView::count();

        // Top 5 Toko (gabungan lead + profile views)
        $topShops = Shop::withCount(['productLeads', 'shopViews'])
            ->orderByDesc('product_leads_count')
            ->orderByDesc('shop_views_count')
            ->take(5)
            ->get();

        return view('admin.analytics.index', compact(
            'totalUsers', 'totalShops', 'totalProducts', 
            'totalWaClicks', 'recentWaClicks', 'recentRegistrations',
            'totalProfileViews', 'topShops'
        ));
    }

    public function editProduct($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function createProduct(Request $request)
    {
        $shop = Shop::findOrFail($request->shop_id);
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('shop', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image_file' => 'required_without:image_url|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_url' => 'required_without:image_file|nullable|url|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('products', 'public');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        \App\Models\Product::create([
            'shop_id' => $request->shop_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'is_active' => true,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk baru berhasil ditambahkan oleh Admin!');
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

    public function toggleHideProduct($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->is_hidden_by_admin = !$product->is_hidden_by_admin;
        $product->save();

        $msg = $product->is_hidden_by_admin ? 'disembunyikan secara sepihak' : 'ditampilkan kembali';
        return back()->with('success', "Produk {$product->name} berhasil {$msg}.");
    }

    public function deleteProduct($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        if ($product->image_path && !str_starts_with($product->image_path, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        
        return back()->with('success', "Produk berhasil dihapus secara permanen.");
    }

    public function usersIndex()
    {
        $users = \App\Models\User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,owner,admin'
        ]);

        $user = \App\Models\User::findOrFail($id);
        
        // Prevent changing own role if it's the only admin, etc.
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        // Jika demosi dari owner ke user, hapus toko beserta produknya
        if ($user->role === 'owner' && $request->role === 'user') {
            $shop = \App\Models\Shop::where('user_id', $user->id)->first();
            if ($shop) {
                // Hapus file gambar produk lokal sebelum menghapus data
                foreach ($shop->products as $product) {
                    if ($product->image_path && !str_starts_with($product->image_path, 'http')) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
                    }
                }
                // Hapus logo toko jika lokal
                if ($shop->logo_path && !str_starts_with($shop->logo_path, 'http')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($shop->logo_path);
                }
                // Hapus toko (produk terhapus otomatis via cascade)
                $shop->delete();
            }
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', "Role {$user->name} berhasil diubah menjadi {$user->role}.");
    }

    public function deleteUser($id)
    {
        $user = \App\Models\User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dihapus permanen.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{

   public function show($id) 
    {
        // Fetch shop with necessary relations
        $shop = \App\Models\Shop::with(['district.regency', 'products.category'])->findOrFail($id);
        
        // Record profile view
        \App\Models\ShopView::create([
            'shop_id' => $shop->id,
            'user_id' => auth()->check() ? auth()->id() : null,
            'ip_address' => request()->ip(),
        ]);

        // Group products by category name for the 'shop.show' view
        $groupedProducts = $shop->products
            ->where('is_active', true)
            ->where('is_hidden_by_admin', false)
            ->groupBy(function($product) {
                return $product->category ? $product->category->name : 'Uncategorized';
            });
        
        return view('shop.show', compact('shop', 'groupedProducts'));
    }

    public function create()
    {

        $existingShop = Shop::where('user_id', Auth::id())->first();

        if ($existingShop) {
            return redirect()->route('dashboard')->with('status', 'Anda sudah mendaftarkan toko. Silakan tunggu persetujuan Admin.');
        }


        $regencies = \App\Models\Regency::with('districts')->where('province_id', 33)->get(); // Jawa Tengah

        return view('shop.create', compact('regencies'));
    }

    public function store(Request $request)
    {
        // 1. Tambahkan 'reason' ke dalam validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'reason' => 'required|string|max:500', // Tambahan validasi alasan
            'district_id' => 'required|exists:districts,id',
            'address_detail' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
        ]);

        // 2. Sanitasi nomor WhatsApp (hapus karakter non-numerik, konversi 0 ke 62)
        $waNumber = preg_replace('/[^0-9]/', '', $request->whatsapp_number);
        if (substr($waNumber, 0, 1) === '0') {
            $waNumber = '62' . substr($waNumber, 1);
        } elseif (substr($waNumber, 0, 2) !== '62') {
            $waNumber = '62' . $waNumber;
        }

        // 3. Simpan data toko ke database
        Shop::create([
            'user_id' => Auth::id(),
            'district_id' => $request->district_id,
            'name' => $request->name,
            'description' => $request->description,
            'reason' => $request->reason, // Tambahan simpan alasan
            'address_detail' => $request->address_detail,
            'whatsapp_number' => $waNumber,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran toko berhasil dikirim! Menunggu persetujuan Admin.');
    }

    public function edit()
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('home')->with('error', 'Toko tidak ditemukan.');
        }
        
        $parentShops = Shop::where('id', '!=', $shop->id)
                           ->where('is_branch', false)
                           ->where('status', 'approved')
                           ->get();

        return view('owner.shop.edit', compact('shop', 'parentShops'));
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $shop = auth()->user()->shop;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'address_detail' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'parent_shop_id' => 'nullable|exists:shops,id'
        ]);

        $data = $request->only(['name', 'whatsapp_number', 'address_detail', 'description']);
        
        $data['is_branch'] = $request->has('is_branch');
        $data['parent_shop_id'] = $request->has('is_branch') ? $request->parent_shop_id : null;

        if ($request->hasFile('logo')) {
            if ($shop->logo_path && !str_starts_with($shop->logo_path, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('images_public')->delete($shop->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('shop_logos', 'images_public');
        }

        $shop->update($data);

        return redirect()->route('owner.shop.edit')->with('success', 'Profil toko berhasil diperbarui!');
    }
}

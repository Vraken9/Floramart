<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Province;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{

    public function create()
    {

        $existingShop = Shop::where('user_id', Auth::id())->first();

        if ($existingShop) {
            return redirect()->route('dashboard')->with('status', 'Anda sudah mendaftarkan toko. Silakan tunggu persetujuan Admin.');
        }


        $provinces = Province::all();

        return view('shop.create', compact('provinces'));
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

        // 2. Tambahkan 'reason' ke proses penyimpanan database
        Shop::create([
            'user_id' => Auth::id(),
            'district_id' => $request->district_id,
            'name' => $request->name,
            'description' => $request->description,
            'reason' => $request->reason, // Tambahan simpan alasan
            'address_detail' => $request->address_detail,
            'whatsapp_number' => $request->whatsapp_number,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran toko berhasil dikirim! Menunggu persetujuan Admin.');
    }
    public function show($id)
    {
        // 1. Cari toko yang sudah di-approve. Jika tidak ada/belum approve, munculkan 404.
        $shop = Shop::with('district.regency', 'user')
            ->where('status', 'approved')
            ->findOrFail($id);

        // 2. Ambil semua bunga yang aktif milik toko ini
        $products = Product::where('shop_id', $shop->id)
            ->where('is_active', true)
            ->latest()
            ->get();

        // 3. Arahkan ke file view di folder resources/views/shop/show.blade.php
        return view('shop.show', compact('shop', 'products'));
    }
}

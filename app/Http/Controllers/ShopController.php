<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Province;
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

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'district_id' => 'required|exists:districts,id',
            'address_detail' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
        ]);

        Shop::create([
            'user_id' => Auth::id(),
            'district_id' => $request->district_id,
            'name' => $request->name,
            'description' => $request->description,
            'address_detail' => $request->address_detail,
            'whatsapp_number' => $request->whatsapp_number,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran toko berhasil dikirim! Menunggu persetujuan Admin.');
    }
}
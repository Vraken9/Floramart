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
}

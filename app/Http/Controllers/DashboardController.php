<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop; // WAJIB DITAMBAHKAN AGAR TIDAK ERROR INTELEPHENSE

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            // Ambil semua toko yang statusnya masih 'pending' beserta data pembuatnya (user)
            $pendingShops = Shop::with('user')->where('status', 'pending')->get();

            // Kirim data tersebut ke halaman view admin
            return view('admin.dashboard', compact('pendingShops'));
        } elseif ($role === 'owner') {
            return view('owner.dashboard');
        }

        return view('dashboard');
    }
}

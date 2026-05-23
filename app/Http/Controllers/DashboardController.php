<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop; // WAJIB DITAMBAHKAN AGAR TIDAK ERROR INTELEPHENSE

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $pendingShops = \App\Models\Shop::with(['user', 'district.regency'])->where('status', 'pending')->get();
            return view('admin.dashboard', compact('pendingShops'));
        } 
        
        if ($user->role === 'owner') {
            return redirect()->route('owner.products.index');
        } 
        
        // Default User Role
        $favoriteProducts = $user->favoriteProducts()->with(['shop.district.regency', 'category'])->get();
        return view('dashboard', compact('favoriteProducts'));
    }
}

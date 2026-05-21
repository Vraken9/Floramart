<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\District;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $districts = District::with('regency.province')->get();

        // LOGIKA 1: JIKA PENGUNJUNG MENGGUNAKAN FILTER/PENCARIAN
        if ($request->filled('category') || $request->filled('district')) {
            $query = Product::with(['category', 'shop.district.regency'])
                ->where('is_active', true)
                ->whereHas('shop', function($q) {
                    $q->where('status', 'approved');
                });

            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }
            if ($request->filled('district')) {
                $query->whereHas('shop', function($q) use ($request) {
                    $q->where('district_id', $request->district);
                });
            }

            $products = $query->latest()->get();
            $isSearch = true; // Penanda bahwa ini halaman hasil pencarian

            return view('welcome', compact('products', 'categories', 'districts', 'isSearch'));
        }

        // LOGIKA 2: JIKA HALAMAN DEPAN NORMAL (Menampilkan Baris per Kategori)
        // Kita ambil kategori beserta relasi produknya (maksimal 4 produk per kategori untuk preview)
        $groupedProducts = Category::with(['products' => function($q) {
            $q->where('is_active', true)
              ->whereHas('shop', function($sq) {
                  $sq->where('status', 'approved');
              })
              ->latest()
              ->take(4); // Hanya ambil 4 terbaru per kategori
        }, 'products.shop.district.regency'])->get();

        $isSearch = false; // Penanda bahwa ini beranda normal

        return view('welcome', compact('groupedProducts', 'categories', 'districts', 'isSearch'));
    }
    // Tambahkan fungsi ini di bawah fungsi index()
    public function show($slug)
    {
        // Cari produk berdasarkan slug, pastikan statusnya aktif, dan bawa data relasinya
        $product = Product::with(['category', 'shop.district.regency', 'shop.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categories = Category::all();

        return view('product.show', compact('product', 'categories'));
    }

    public function allShops(Illuminate\Http\Request $request)
    {
        $districts = \App\Models\District::with('regency.province')->get();
        $query = \App\Models\Shop::with('district.regency')->where('status', 'approved');

        if ($request->filled('district')) {
            $query->where('district_id', $request->district);
        }

        $shops = $query->latest()->get();
        $categories = \App\Models\Category::all();

        return view('shop.index', compact('shops', 'districts', 'categories'));
    }
}

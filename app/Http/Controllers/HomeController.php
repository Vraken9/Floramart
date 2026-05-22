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

        // Kita ambil kategori beserta relasi produknya (maksimal 4 produk per kategori untuk preview)
        $groupedProducts = Category::with(['products' => function($q) {
            $q->where('is_active', true)
              ->whereHas('shop', function($sq) {
                  $sq->where('status', 'approved');
              })
              ->latest()
              ->take(4); // Hanya ambil 4 terbaru per kategori
        }, 'products.shop.district.regency'])->get();

        return view('welcome', compact('groupedProducts', 'categories', 'districts'));
    }
    public function katalog(\Illuminate\Http\Request $request)
    {
        $categories = \App\Models\Category::all();
        $regencies = \App\Models\Regency::with('districts')->where('province_id', 33)->get();

        $query = \App\Models\Product::with(['category', 'shop.district.regency'])
            ->where('is_active', true)
            ->whereHas('shop', function($q) {
                $q->where('status', 'approved');
            });

        // Cek apakah user sedang melakukan pencarian/filter
        $isSearch = $request->filled('search') || $request->filled('category') || $request->filled('regency') || $request->filled('district');

        if ($isSearch) {
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }
            if ($request->filled('regency')) {
                $query->whereHas('shop.district', function($q) use ($request) {
                    $q->where('regency_id', $request->regency);
                });
            }
            if ($request->filled('district')) {
                $query->whereHas('shop', function($q) use ($request) {
                    $q->where('district_id', $request->district);
                });
            }

            $products = $query->latest()->get();
            $groupedProducts = collect(); // Kosongkan saat mode pencarian
        } else {
            $products = collect(); // Kosongkan grid normal

            // Ambil produk dan kelompokkan per kategori (maksimal 4 per kategori untuk etalase)
            $groupedProducts = \App\Models\Category::with(['products' => function($q) {
                $q->where('is_active', true)
                  ->whereHas('shop', function($sq) {
                      $sq->where('status', 'approved');
                  })->latest()->take(4);
            }])->get();
        }

        return view('katalog.index', compact('products', 'groupedProducts', 'categories', 'regencies', 'isSearch'));
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

    public function allShops(Request $request)
    {
        $regencies = \App\Models\Regency::with('districts')->where('province_id', 33)->get();
        $query = \App\Models\Shop::with('district.regency')->where('status', 'approved');

        if ($request->filled('district')) {
            $query->where('district_id', $request->district);
        }

        $shops = $query->latest()->get();
        $categories = \App\Models\Category::all();

        return view('shop.index', compact('shops', 'districts', 'categories'));
    }
}

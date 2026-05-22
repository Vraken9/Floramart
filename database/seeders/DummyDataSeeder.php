<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. DYNAMIC REGION DATA (JAWA TENGAH) VIA PUBLIC API
        $jateng = Province::firstOrCreate(['id' => 33], ['name' => 'JAWA TENGAH']);
        
        $this->command->info('Fetching Regencies and Districts for Jawa Tengah (This may take a moment)...');
        $regencies = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/regencies/33.json')->json();
        
        foreach ($regencies as $reg) {
            $regency = Regency::firstOrCreate(['id' => $reg['id'], 'province_id' => 33], ['name' => $reg['name']]);
            $districts = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$reg['id']}.json")->json();
            foreach ($districts as $dist) {
                District::firstOrCreate(['id' => $dist['id'], 'regency_id' => $regency->id], ['name' => $dist['name']]);
            }
        }

        // Get safe fallback districts for dummy shops
        $kecMandiraja = District::where('name', 'MANDIRAJA')->first() ?? District::first();
        $kecBaturraden = District::where('name', 'BATURRADEN')->first() ?? District::skip(1)->first();

        // 2. DATA USER & OWNER
        $owner1 = User::firstOrCreate(['email' => 'owner1@floramart.test'], ['name' => 'Budi Owner', 'password' => Hash::make('password'), 'role' => 'owner']);
        $owner2 = User::firstOrCreate(['email' => 'owner2@floramart.test'], ['name' => 'Siti Florist', 'password' => Hash::make('password'), 'role' => 'owner']);

        // 3. DATA TOKO (With Logos)
        $shop1 = Shop::firstOrCreate(
            ['user_id' => $owner1->id],
            [
                'name' => 'Budi Indah Florist', 'district_id' => $kecMandiraja->id, 'whatsapp_number' => '081234567890', 
                'status' => 'approved', 'description' => 'Toko spesialis pernikahan.', 'reason' => '', 'address_detail' => 'Jl. Klampok',
                'logo_path' => 'https://images.unsplash.com/photo-1545228800-47b2c011e4f4?w=100&q=80' // Shop Logo
            ]
        );
        $shop2 = Shop::firstOrCreate(
            ['user_id' => $owner2->id],
            [
                'name' => 'Siti Asri Bunga', 'district_id' => $kecBaturraden->id, 'whatsapp_number' => '089876543210', 
                'status' => 'approved', 'description' => 'Bunga segar petani.', 'reason' => '', 'address_detail' => 'Jl. Baturraden',
                'logo_path' => 'https://images.unsplash.com/photo-1579216709848-0d12e6900223?w=100&q=80' // Shop Logo
            ]
        );

        // 4. KATEGORI & PRODUK
        $catPernikahan = Category::firstOrCreate(['name' => 'Buket Pernikahan'], ['slug' => 'buket-pernikahan']);
        $catDuka = Category::firstOrCreate(['name' => 'Bunga Papan Duka Cita'], ['slug' => 'bunga-papan-duka-cita']);

        $products = [
            ['shop_id' => $shop1->id, 'category_id' => $catPernikahan->id, 'name' => 'Buket Premium', 'price' => 250000, 'description' => 'Bunga kualitas premium.', 'image_path' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55ef6?w=800&q=80', 'is_active' => true],
            ['shop_id' => $shop2->id, 'category_id' => $catDuka->id, 'name' => 'Papan Spesial', 'price' => 750000, 'description' => 'Ukuran besar 2x1.5m.', 'image_path' => 'https://images.unsplash.com/photo-1583097148560-6b2257d07dc7?w=800&q=80', 'is_active' => true]
        ];

        foreach ($products as $prod) {
            $prod['slug'] = Str::slug($prod['name']) . '-' . uniqid();
            Product::firstOrCreate(['name' => $prod['name']], $prod);
        }
    }
}

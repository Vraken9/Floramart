<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use App\Models\District;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $districts = District::all();
        $categories = Category::all();

        // Jika data wilayah/kategori kosong, batalkan proses
        if ($districts->isEmpty() || $categories->isEmpty()) {
            $this->command->info('Data Kecamatan atau Kategori kosong. Pastikan DatabaseSeeder sudah dijalankan.');
            return;
        }

        // Data Skenario Toko Dummy
        $shopsData = [
            ['owner' => 'Budi Florist', 'email' => 'budi@floramart.test', 'shop_name' => 'Budi Indah Florist'],
            ['owner' => 'Siti Bunga', 'email' => 'siti@floramart.test', 'shop_name' => 'Siti Asri Bunga'],
            ['owner' => 'Agus Bouquet', 'email' => 'agus@floramart.test', 'shop_name' => 'Agus Premium Bouquet'],
        ];

        foreach ($shopsData as $index => $data) {
            // 1. Buat Akun Owner
            $owner = User::create([
                'name' => $data['owner'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'role' => 'owner',
            ]);

            // 2. Buat Toko (Langsung berstatus Approved agar muncul di beranda)
            $shop = Shop::create([
                'user_id' => $owner->id,
                'district_id' => $districts->random()->id, // Pilih kecamatan secara acak
                'name' => $data['shop_name'],
                'description' => 'Toko bunga terbaik dan terpercaya melayani pesanan cepat. Kondisi bunga segar langsung dari kebun.',
                'reason' => 'Ingin memperluas pasar secara digital.',
                'address_detail' => 'Jl. Bunga Raya No. ' . rand(10, 99),
                'whatsapp_number' => '62812345678' . $index,
                'status' => 'approved',
            ]);

            // 3. Buat 4 Produk Bunga untuk setiap toko
            for ($i = 1; $i <= 4; $i++) {
                $productName = 'Buket Spesial ' . $data['owner'] . ' Tipe ' . $i;
                Product::create([
                    'shop_id' => $shop->id,
                    'category_id' => $categories->random()->id, // Pilih kategori acak
                    'name' => $productName,
                    'slug' => Str::slug($productName) . '-' . time() . rand(100, 999),
                    'description' => 'Bunga kualitas premium dirangkai oleh profesional. Cocok untuk hadiah wisuda, ulang tahun, atau perayaan lainnya.',
                    'price' => rand(50, 350) * 1000, // Harga acak Rp 50.000 s/d Rp 350.000
                    // Menggunakan layanan placeholder gambar acak agar UI terlihat cantik
                    'image_path' => 'https://picsum.photos/seed/' . rand(1, 9999) . '/600/400',
                    'is_active' => true,
                ]);
            }
        }
    }
}

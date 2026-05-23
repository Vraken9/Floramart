<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Update semua nomor WhatsApp toko menjadi nomor admin
        Shop::query()->update(['whatsapp_number' => '6289530123608']);
        $this->command->info('Semua nomor WhatsApp toko telah diubah menjadi 6289530123608.');

        // 2. Kumpulan gambar bunga spesifik dari Unsplash
        $flowerImages = [
            'https://images.unsplash.com/photo-1563241527-3004b7be0ffd?q=80&w=800',
            'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?q=80&w=800',
            'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=800',
            'https://images.unsplash.com/photo-1457089328109-e5d9f725f5a2?q=80&w=800',
            'https://images.unsplash.com/photo-1508610048659-a06b669e3321?q=80&w=800',
            'https://images.unsplash.com/photo-1613539246066-78db6ec4ff0f?q=80&w=800',
            'https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=800',
            'https://images.unsplash.com/photo-1562690868-60bbe7293e94?q=80&w=800',
            'https://images.unsplash.com/photo-1542301018-8798bf2fc6ff?q=80&w=800',
            'https://images.unsplash.com/photo-1490750967868-88cb44cb2e44?q=80&w=800',
            'https://images.unsplash.com/photo-1505391847043-8b6e24dd6c96?q=80&w=800',
            'https://images.unsplash.com/photo-1587595431973-160d0d94add1?q=80&w=800',
            'https://images.unsplash.com/photo-1416879598056-0cbb04922ba4?q=80&w=800',
            'https://images.unsplash.com/photo-1459156212016-c812468e2115?q=80&w=800',
            'https://images.unsplash.com/photo-1507025531068-dceeb4a05f15?q=80&w=800',
        ];

        // 3. Timpa semua gambar produk lama dengan gambar bunga
        $existingProducts = Product::all();
        foreach ($existingProducts as $product) {
            $product->update([
                'image_path' => $flowerImages[array_rand($flowerImages)]
            ]);
        }
        $this->command->info('Semua gambar produk eksisting telah diubah.');

        // 4. Generate 15 produk baru untuk setiap toko yang ada
        $shops = Shop::all();
        $categories = Category::all();

        if ($shops->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Tidak ada toko atau kategori untuk diisi data dummy.');
            return;
        }

        $flowerNames = [
            'Buket Mawar Merah Premium',
            'Buket Tulip Putih Elegan',
            'Standing Flower Congratulation',
            'Bunga Papan Duka Cita Eksklusif',
            'Buket Matahari Ceria',
            'Buket Anggrek Ungu Menawan',
            'Bunga Meja Krisan Segar',
            'Buket Bunga Lily Harum',
            'Buket Baby Breath Kekinian',
            'Tanaman Hias Monstera Deliciosa',
            'Dekorasi Bunga Kering Aesthetic',
            'Buket Bunga Kertas Pastel',
            'Standing Flower Grand Opening',
            'Bunga Papan Happy Wedding',
            'Buket Mawar Pink Romantis',
            'Kaktus Hias Mini Indoor',
            'Bunga Meja Anggrek Bulan',
            'Buket Bunga Daisy Lucu',
            'Parcel Bunga & Buah Segar',
            'Buket Hydrangea Mewah'
        ];

        $productCount = 0;

        foreach ($shops as $shop) {
            // Kita buat 15 produk untuk tiap toko
            for ($i = 0; $i < 15; $i++) {
                $name = $flowerNames[array_rand($flowerNames)] . ' - Edisi ' . Str::random(4);
                $category = $categories->random();
                
                Product::create([
                    'shop_id' => $shop->id,
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . uniqid(),
                    'description' => 'Ini adalah produk bunga segar dan berkualitas dari toko kami. Cocok untuk berbagai macam acara dan momen spesial Anda. Kami menjamin kualitas dan kesegaran bunga hingga sampai ke tangan penerima.',
                    'price' => rand(10, 200) * 10000, // Harga antara Rp 100.000 s.d Rp 2.000.000
                    'image_path' => $flowerImages[array_rand($flowerImages)],
                    'is_active' => true,
                ]);
                $productCount++;
            }
        }

        $this->command->info("Berhasil membuat {$productCount} produk dummy baru yang tersebar di " . $shops->count() . " toko.");
    }
}

# Panduan Belajar MVC: 04. Katalog Publik & Penciptaan Lead (WhatsApp)

Bagian ini adalah ujung tombak dari platform FloraMart. Bagaimana pengguna awam melihat produk, dan bagaimana klik "Pesan" diubah menjadi pesan WhatsApp.

## 1. Alur Menampilkan Katalog (Optimasi Kinerja Eager Loading)

Halaman depan (Katalog) dapat diakses tanpa harus login.

1. **Rute (Router):** Saat pengguna masuk ke halaman utama atau halaman katalog.
   ```php
   // Letak: routes/web.php
   Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog.index');
   ```
2. **Controller (Mengambil Data Cerdas):** Tugas Controller di sini sangat penting. Ia harus mengambil data Produk, tapi sebuah produk selalu menempel pada Toko (`Shop`) dan Kategori (`Category`). Jika Controller memanggilnya satu per satu, *database* bisa meledak karena kelebihan beban (Ini disebut "N+1 Query Problem").
   Untuk mencegahnya, kita menggunakan teknik *Eager Loading* (`with`).
   ```php
   // Letak: app/Http/Controllers/HomeController.php
   public function katalog()
   {
       // "Tolong ambil semua Produk yang aktif (is_active = 1), 
       // dan SEKALIAN bawa data 'shop' serta 'category'-nya dalam satu kali jalan."
       $products = \App\Models\Product::with(['shop', 'category'])
                   ->where('is_active', true)
                   ->get();

       // Kirim ke View
       return view('katalog.index', compact('products'));
   }
   ```
3. **View:** View tinggal melooping (mengulang) isi `$products` menggunakan pola HTML (Blade).
   ```html
   <!-- Letak: resources/views/katalog/index.blade.php -->
   @foreach($products as $bunga)
       <h3>{{ $bunga->name }}</h3>
       <p>Dijual oleh: {{ $bunga->shop->name }}</p>
       <p>Kategori: {{ $bunga->category->name }}</p>
   @endforeach
   ```

---

## 2. Alur "Direct Lead" (Eksekusi Pesanan WhatsApp)

FloraMart membuang sistem keranjang (*Cart*) tradisional karena negosiasi bunga (custom warna pita, kartu ucapan) lebih baik dilakukan via WhatsApp.

### A. Klik Tombol Pesan
1. **View:** Di halaman katalog, setiap produk memiliki tombol "Pesan Sekarang" yang mengarah ke URL khusus.
   ```html
   <a href="{{ route('product.whatsapp', $bunga->id) }}">Pesan Sekarang</a>
   ```

### B. Pencatatan dan Pembuatan Link di Controller
1. **Rute (Router):** Menangkap klik tersebut dan melemparnya ke `LeadController`.
   ```php
   Route::get('/bunga/{id}/wa-redirect', [\App\Http\Controllers\LeadController::class, 'redirectWhatsApp']);
   ```
2. **Controller:** Di sinilah "otak" pengolahan pesanan WhatsApp bekerja. Ia akan (1) mencatat log ketertarikan, (2) memformat nomor telepon penjual, (3) menulis draf pesan, lalu (4) melempar pelanggan ke aplikasi WhatsApp.
   ```php
   // Letak: app/Http/Controllers/LeadController.php
   public function redirectWhatsApp($id)
   {
       // Cari produk dan tokonya
       $product = Product::with('shop')->findOrFail($id);

       // [Tahap 1] Analitik: Simpan catatan bahwa produk ini diklik
       ProductLead::insert([
           'product_id' => $product->id,
           'shop_id' => $product->shop_id,
           'user_id' => Auth::id(), // Siapa yang klik
           'clicked_at' => now(),
       ]);

       // [Tahap 2] Format Nomor WhatsApp Pemilik Toko
       $phone = $product->shop->whatsapp_number;
       // Hilangkan karakter aneh
       $phone = preg_replace('/[^0-9]/', '', $phone);
       // Jika diawali angka 0, ubah ke kode negara Indonesia (62)
       if (substr($phone, 0, 1) === '0') {
           $phone = '62' . substr($phone, 1);
       }

       // [Tahap 3] Merakit draf pesan otomatis
       $formattedPrice = number_format($product->price, 0, ',', '.');
       $message = "Halo {$product->shop->name}, saya tertarik untuk memesan bunga *{$product->name}* seharga Rp {$formattedPrice} yang saya lihat di FloraMart.";

       // [Tahap 4] Lempar (Redirect) pengguna ke server WhatsApp (Keluar dari web FloraMart)
       $url = 'https://wa.me/' . $phone . '?text=' . urlencode($message);
       return redirect()->away($url);
   }
   ```
Inilah mengapa disebut *Lead Generation*. Kita mencatat minat pelanggan (Lead) di database, tapi transaksi finansial dan percakapan diteruskan keluar sistem (WhatsApp). Pendekatan arsitektur ini sangat cocok untuk produk UMKM kustom (B2C Langsung).

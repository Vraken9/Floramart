# Panduan Belajar MVC: 03. Manajemen Produk (Katalog Owner)

Hanya pengguna dengan status **Owner** (yang tokonya telah disetujui Admin) yang bisa mengakses fitur ini. Mari kita lihat alur penambahan produk dan pengubahan status ketersediaan barang.

## 1. Alur Menambah Produk Baru (Create & Upload Image)

### A. Rute dan Middleware (Penjaga Pintu)
```php
// Letak: routes/web.php
Route::middleware(['auth', 'role:owner'])->group(function () {
    // ...
    Route::post('/owner/products', [ProductController::class, 'store'])->name('owner.products.store');
});
```
Perhatikan `role:owner`. Jika *User* biasa mencoba mengirim data ke URL ini, sistem akan langsung memblokirnya (Error 403 Forbidden).

### B. Menerima dan Menyimpan Gambar di Controller
Ketika Owner mengunggah foto bunga dari laptopnya dan menekan tombol Simpan, inilah yang dipikirkan dan dilakukan oleh Controller:

```php
// Letak: app/Http/Controllers/ProductController.php
public function store(Request $request)
{
    // 1. Ambil data toko milik Owner yang sedang login
    $shop = Shop::where('user_id', Auth::id())->first();

    // 2. Controller menyiapkan variabel kosong untuk menyimpan lokasi foto
    $imagePath = null;
    
    // 3. Apakah ada file foto yang diunggah?
    if ($request->hasFile('image_file')) {
        // Jika ada, Controller meminjam "Fasad Storage" dari Laravel
        // untuk memindahkan foto dari memory sementara ke folder 'public/products'
        $imagePath = $request->file('image_file')->store('products', 'public');
    }

    // 4. Controller menyuruh Model Product untuk membuat data baru
    Product::create([
        'shop_id' => $shop->id,               // Hubungkan bunga ini dengan toko si pembuat
        'category_id' => $request->category_id, 
        'name' => $request->name,
        // ... (data lainnya)
        'image_path' => $imagePath,           // Simpan Teks lokasi foto (misal: products/mawar.jpg)
        'is_active' => true,                  // Secara default bunga langsung aktif/tersedia
    ]);

    // 5. Kembalikan Owner ke halaman tabel produk dengan pesan sukses
    return redirect()->route('owner.products.index')->with('success', 'Bunga baru ditambahkan!');
}
```

### C. Menampilkan Gambar di View
Ketika View ingin menampilkan gambar bunga tersebut, View tidak bisa membaca *database* secara langsung. Ia menerima variabel `$product` dari Controller lalu menyuntikkan lokasi gambarnya (`$product->image_path`) ke dalam tag HTML `<img>`.
```html
<!-- Letak: resources/views/owner/products/index.blade.php -->
<img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
```

---

## 2. Alur "Toggle" Visibilitas Produk (Sembunyikan / Tampilkan)

Seringkali stok bunga kosong, tapi Owner tidak ingin menghapus datanya secara permanen. FloraMart menggunakan metode "Toggle" (Saklar).

1. **Rute (Router):** Owner menekan tombol "Sembunyikan".
   ```php
   Route::patch('/owner/products/{id}/toggle', [ProductController::class, 'toggleStatus']);
   ```
2. **Controller (Logika Pembalik):** Controller mencari produk tersebut, lalu membalikkan status `is_active` nya dari *True* ke *False*, atau sebaliknya.
   ```php
   // Letak: app/Http/Controllers/ProductController.php
   public function toggleStatus($id)
   {
       // Ambil data toko si Owner
       $shop = Shop::where('user_id', Auth::id())->first();
       
       // Pastikan produk yang dicari benar-benar MILIK toko si Owner
       $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

       // Logika Saklar (Toggle)
       // Jika sebelumnya true, jadikan false (!true = false)
       $product->is_active = !$product->is_active;
       
       // Simpan kembali ke database
       $product->save();

       return redirect()->route('owner.products.index')->with('success', 'Status ketersediaan diubah!');
   }
   ```
Metode ini jauh lebih aman dan ringan bagi *database* dibandingkan proses `Delete` dan `Create` ulang. Trik validasi ekstra di Controller (`where('shop_id', $shop->id)`) juga memastikan Owner tidak bisa iseng mematikan/menyembunyikan produk milik toko orang lain.

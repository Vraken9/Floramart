# Panduan Belajar MVC: 02. Manajemen Toko & Hak Akses (Role)

Dokumen ini menjelaskan bagaimana pengguna biasa (User) bisa berubah statusnya menjadi pemilik toko (Owner) melalui proses pendaftaran dan persetujuan oleh Admin.

## 1. Alur Pendaftaran Toko (User -> Calon Owner)

### A. Membuka Formulir "Buka Toko"
1. **Rute (Router):** Pengguna mengklik tombol "Buka Toko".
   ```php
   // Letak: routes/web.php
   Route::get('/buka-toko', [ShopController::class, 'create'])->name('shop.create');
   ```
2. **Controller:** Controller bertugas mengambil data lokasi (Kabupaten/Kota) dari database agar pengguna bisa memilih lokasi toko mereka melalui *dropdown*.
   ```php
   // Letak: app/Http/Controllers/ShopController.php
   public function create()
   {
       // Minta Model Regency mengambil data semua kabupaten/kota beserta kecamatannya
       $regencies = \App\Models\Regency::with('districts')->get();
       
       // Kirim data tersebut ke View
       return view('shop.create', compact('regencies'));
   }
   ```
3. **View:** File `resources/views/shop/create.blade.php` menerima `$regencies` dan menampilkannya sebagai `<option>` dalam tag `<select>`.

### B. Menyimpan Data Pendaftaran Toko
1. **Rute (Router):** Data formulir dikirim via POST.
   ```php
   // Letak: routes/web.php
   Route::post('/buka-toko', [ShopController::class, 'store'])->name('shop.store');
   ```
2. **Controller:** Fungsi `store` di `ShopController` menerima data. Di sini, controller memformat nomor WhatsApp (mengubah awalan 0 menjadi 62) dan menyimpan data toko dengan status **'pending'**.
   ```php
   // Letak: app/Http/Controllers/ShopController.php
   public function store(Request $request)
   {
       // 1. Sanitasi & Format Nomor WA
       $waNumber = preg_replace('/[^0-9]/', '', $request->whatsapp_number);
       if (substr($waNumber, 0, 1) === '0') {
           $waNumber = '62' . substr($waNumber, 1);
       }
       // ...
       
       // 2. Simpan Data via Model
       Shop::create([
           'user_id' => Auth::id(), // ID pengguna yang sedang login
           'regency_id' => $request->regency_id,
           'name' => $request->name,
           'whatsapp_number' => $waNumber,
           'status' => 'pending', // Menunggu persetujuan Admin
       ]);
       
       return redirect()->route('dashboard')->with('success', 'Pendaftaran toko berhasil...');
   }
   ```

---

## 2. Alur Persetujuan oleh Admin (Approval Workflow)

Hanya pengguna dengan Role 'admin' yang bisa menyetujui toko. Rute ini dijaga ketat oleh Middleware.

### A. Mengubah Status dan Peran (Role)
1. **Rute (Router):** Admin menekan tombol "Setujui" di halaman dasbor mereka.
   ```php
   // Letak: routes/web.php
   Route::patch('/shops/{id}/approve', [AdminController::class, 'approveShop'])
        ->middleware(['auth', 'role:admin']); // Penjaga Pintu (Middleware)
   ```
2. **Controller:** Controller mencari toko tersebut di database, mengubah statusnya menjadi 'approved', lalu mencari Model User pemilik toko itu dan mengubah *role*-nya dari 'user' menjadi 'owner'.
   ```php
   // Letak: app/Http/Controllers/AdminController.php
   public function approveShop($id)
   {
       // 1. Cari toko berdasarkan ID
       $shop = \App\Models\Shop::findOrFail($id);
       
       // 2. Ubah status toko
       $shop->status = 'approved';
       $shop->save();

       // 3. Ubah hak akses / peran (Role) pemiliknya
       $user = \App\Models\User::find($shop->user_id);
       $user->role = 'owner';
       $user->save();

       // 4. Kembalikan ke halaman sebelumnya dengan pesan sukses
       return back()->with('success', 'Toko berhasil disetujui. Pengguna sekarang menjadi Owner.');
   }
   ```
3. **Model:** Model `Shop` dan `User` (`app/Models/Shop.php` & `app/Models/User.php`) bekerja di belakang layar untuk mengeksekusi perintah `$shop->save()` dan memperbarui baris data di MySQL.

**Apa yang terjadi setelah ini?**
Karena peran pengguna di database sudah berubah menjadi `owner`, maka setiap kali pengguna tersebut login, kode penjaga (Middleware) bernama `role:owner` akan memberikan mereka izin untuk mengakses rute manajemen produk (seperti menambah/mengedit barang).

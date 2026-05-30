# Panduan Belajar MVC: 01. Autentikasi & Verifikasi Email

Dokumen ini menjelaskan alur kerja (flow) dari pendaftaran akun hingga verifikasi email dalam arsitektur Model-View-Controller (MVC) di proyek FloraMart.

## 1. Alur Pendaftaran (Register)

Ketika pengguna ingin membuat akun baru, inilah yang terjadi di belakang layar:

### A. Membuka Halaman Register
1. **Rute (Router):** Pengguna mengetik `/register`. Router di `routes/auth.php` menangkap URL ini.
   ```php
   // Letak: routes/auth.php
   Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
   ```
2. **Controller:** Router memanggil `RegisteredUserController` dan menjalankan fungsi `create()`. Fungsi ini sangat sederhana, ia hanya bertugas memanggil tampilan HTML.
   ```php
   // Letak: app/Http/Controllers/Auth/RegisteredUserController.php
   public function create(): View
   {
       return view('auth.register'); // Memanggil View
   }
   ```
3. **View:** File tampilan HTML yang akan dikirim ke layar pengguna. Letaknya ada di `resources/views/auth/register.blade.php`.

### B. Menyimpan Data Registrasi
1. **Rute (Router):** Setelah pengguna mengisi nama, email, dan password lalu menekan "Daftar", data dikirim ke pintu `POST /register`.
   ```php
   // Letak: routes/auth.php
   Route::post('register', [RegisteredUserController::class, 'store']);
   ```
2. **Controller:** Router mengarahkan data ini ke fungsi `store()`. Di sini, Controller memeriksa validitas data, melakukan enkripsi (hash) pada kata sandi, dan menyuruh Model untuk menyimpannya.
   ```php
   // Letak: app/Http/Controllers/Auth/RegisteredUserController.php
   public function store(Request $request): RedirectResponse
   {
       // 1. Controller mengecek kelengkapan data
       $request->validate([
           'name' => ['required', 'string', 'max:255'],
           'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
           'password' => ['required', 'confirmed', Rules\Password::defaults()],
       ]);

       // 2. Controller menyuruh Model User untuk menyimpan data ke database
       $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => Hash::make($request->password), // Kata sandi diacak demi keamanan
       ]);

       // 3. Controller memberi tanda bahwa pengguna sudah login
       Auth::login($user);

       // 4. Mengarahkan kembali ke halaman dashboard (View)
       return redirect(route('dashboard', absolute: false));
   }
   ```
3. **Model:** Model `User` (`app/Models/User.php`) adalah representasi dari tabel `users` di database. Ia menerima perintah `create` dari Controller dan memasukkan data tersebut ke dalam MySQL.

---

## 2. Alur Verifikasi Email

Proyek FloraMart mengharuskan pengguna memverifikasi email mereka agar fitur-fitur aman dari akun bot/palsu.

1. **Model:** Perhatikan file Model User Anda. Terdapat tulisan `implements MustVerifyEmail`. Ini adalah perintah sakti yang memberitahu Laravel: *"Setiap ada akun baru yang disimpan, otomatis kirimkan email verifikasi kepadanya!"*
   ```php
   // Letak: app/Models/User.php
   class User extends Authenticatable implements MustVerifyEmail
   {
       // ...
   }
   ```
2. **Pengiriman Email:** Laravel mengirimkan tautan (link) khusus ke email pengguna. Tautan ini dilengkapi dengan *Signature* (Tanda tangan kriptografi) yang aman.
3. **Rute (Router):** Saat pengguna mengeklik tautan di email mereka, mereka akan dibawa ke URL unik yang ditangkap oleh rute ini:
   ```php
   // Letak: routes/auth.php
   Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
       ->middleware(['auth', 'signed', 'throttle:6,1']) // Pintu dijaga ketat oleh middleware
       ->name('verification.verify');
   ```
4. **Controller:** `VerifyEmailController` mengecek apakah tanda tangannya valid. Jika valid, Controller akan mencatat waktu verifikasi (`email_verified_at`) ke database, dan mengarahkan pengguna ke halaman Dasbor.

**Catatan Khusus (Middleware `verified`):**
Jika Anda melihat di `routes/web.php`, beberapa rute dijaga oleh penjaga bernama `verified`.
```php
// Letak: routes/web.php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified']) // Hanya yang sudah verifikasi email yang boleh masuk
    ->name('dashboard');
```
Jika pengguna belum memverifikasi emailnya, *Middleware* ini akan menendang mereka ke halaman peringatan verifikasi (`resources/views/auth/verify-email.blade.php`), sebelum mereka bisa menyentuh Controller `DashboardController`.

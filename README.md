FloraMart - Marketplace Toko Bunga Berbasis Lokasi Pulau Jawa
Tujuan Proyek
FloraMart dirancang sebagai platform multi-tenant khusus untuk menghubungkan pelaku UMKM toko bunga tradisional dengan calon pembeli secara lokal. Industri bunga memiliki karakteristik unik di mana produk yang dijual bersifat cepat layu dan seringkali dibutuhkan dalam waktu singkat untuk momen-momen penting seperti wisuda, pernikahan, atau duka cita. Oleh karena itu, FloraMart hadir dengan fokus penyelesaian pada dua aspek utama: pencarian berbasis kedekatan geografis dan penyederhanaan alur transaksi.

Dengan membatasi ruang lingkup wilayah pada Pulau Jawa secara terstruktur, platform ini memungkinkan pengguna untuk menemukan toko bunga terdekat dari lokasi mereka guna memastikan bunga sampai dalam kondisi segar melalui pengiriman instan. Selain itu, alih-alih menggunakan sistem keranjang belanja konvensional yang rumit, FloraMart menggunakan pendekatan Minimum Viable Product (MVP) yang mengarahkan pembeli langsung ke WhatsApp Pemilik Toko (Owner). Langkah ini diambil untuk menciptakan interaksi transaksi yang lebih hangat, fleksibel terhadap kustomisasi buket bunga, sekaligus efisien dari sisi pengembangan sistem.

Arsitektur Data, Relasi, dan Kegunaan Fitur
Seluruh fungsionalitas yang ada pada aplikasi FloraMart ditopang oleh enam komponen tabel database utama yang saling berelasi secara terintegrasi untuk mendukung peran tiga hak akses pengguna (User, Owner, Admin):

1. Manajemen Akun dan Kontrol Akses (Tabel users)
Tabel users berfungsi sebagai fondasi utama autentikasi untuk mengenali setiap individu yang berinteraksi di dalam platform. Di dalam tabel ini, kolom role memegang peranan krusial dalam menerapkan Role-Based Access Control (RBAC).

Fitur Utama: Sistem secara otomatis membedakan tampilan antarmuka dan hak akses berdasarkan peran pengguna. Akun dengan peran user hanya dapat menjelajah dan memberikan pesan, peran owner akan mendapatkan akses ke dasbor toko mereka sendiri, sedangkan peran admin memegang kendali tertinggi untuk menyetujui pendaftaran toko baru, menonaktifkan toko yang melanggar, atau menurunkan status peran pengguna.

2. Standardisasi Lokasi Wilayah (Tabel provinces, regencies, districts)
Ketiga tabel ini membentuk hierarki lokasi yang ternormalisasi (Provinsi -> Kabupaten/Kota -> Kecamatan) untuk mencakup seluruh wilayah di Pulau Jawa.

Fitur Utama: Ketika seorang Owner mendaftarkan tokonya, mereka diwajibkan memilih lokasi berdasarkan data resmi yang telah disediakan oleh Admin melalui pilihan bertingkat. Struktur data yang rapi ini mendukung fitur Filter Pencarian Lokasi, sehingga pembeli dapat dengan mudah menyaring toko bunga yang hanya berada di kecamatan atau kota mereka sendiri tanpa risiko kesalahan pengetikan alamat.

3. Profil dan Legalitas Toko (Tabel shops)
Tabel shops terhubung langsung dengan tabel users melalui relasi One-to-One, memastikan satu akun pemilik hanya dapat mengelola satu unit toko bunga. Tabel ini juga mengikat data lokasi melalui relasi dengan tabel districts.

Fitur Utama: Tabel ini menyimpan informasi esensial toko termasuk nomor kontak operasional (whatsapp_number). Kolom status (pending, approved, suspended) digunakan untuk mendukung alur kerja persetujuan Admin. Toko yang baru mendaftar tidak akan muncul di halaman utama pembeli sebelum diubah statusnya menjadi approved oleh Admin.

4. Jadwal Operasional Toko (Tabel shop_schedules)
Untuk memberikan fleksibilitas waktu bagi pemilik toko, tabel shop_schedules memecah jadwal operasional berdasarkan hari dalam satu pekan dengan memanfaatkan representasi angka indeks.

Fitur Utama: Data jam buka, jam tutup, serta penanda status libur pada tabel ini digunakan untuk menghidupkan fitur Indikator Status Toko secara otomatis. Ketika pembeli melihat profil toko, sistem Laravel akan mencocokkan waktu lokal server saat ini dengan jadwal toko tersebut untuk menampilkan status apakah toko sedang "Buka" atau "Tutup".

5. Standardisasi Katalog dan Produk (Tabel categories dan products)
Tabel categories dikelola sepenuhnya oleh Admin sebagai pustaka kategori utama, sedangkan tabel products menyimpan detail properti bunga yang diunggah oleh masing-masing pemilik toko.

Fitur Utama: Pemilik toko dapat mengelompokkan produk mereka ke dalam kategori resmi (seperti Buket Wisuda atau Bunga Papan) untuk mempermudah navigasi pembeli. Kolom image_path disiapkan untuk menampung tautan gambar yang diisolasi pada Google Cloud Storage demi keamanan server. Selain itu, fitur is_active memberikan kendali bagi pemilik toko untuk menyembunyikan katalog bunga tertentu secara sementara jika stok bahan baku sedang kosong tanpa harus menghapus data produk tersebut secara permanen.

6. Mesin Analitik Dashboard (Tabel product_leads)
Karena FloraMart tidak memproses transaksi pembayaran di dalam sistem web, pelacakan konversi penjualan dialihkan melalui pencatatan aktivitas klik. Tabel product_leads merekam setiap kali tombol "Pesan via WhatsApp" ditekan oleh pengunjung, baik yang sudah masuk ke sistem maupun yang menjelajah sebagai tamu (Guest).

Fitur Utama: Setiap ketukan tombol beli akan menghasilkan satu baris data rekam jejak (log) waktu. Akumulasi data pada tabel ini berfungsi sebagai penggerak Fitur Kontrol Analitik di halaman dasbor. Owner dapat melihat grafik tren produk buket mana yang paling banyak diminati dalam kurun waktu tertentu, sementara Admin dapat melihat statistik agregat performa seluruh toko yang aktif di dalam platform FloraMart.
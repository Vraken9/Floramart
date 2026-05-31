<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AccessibilityController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate([
            'image_base64' => 'required|string',
            'colorblind_type' => 'required|string'
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return response()->json(['error' => 'API Key Gemini belum dikonfigurasi di server.'], 500);
        }

        $prompt = "Kamu adalah asisten panduan visual yang empatik dan detail untuk pengguna dengan kondisi buta warna " . $request->colorblind_type . ". 
Tugasmu adalah menganalisis antarmuka (UI) pada gambar ini dan memberikan petunjuk navigasi yang sangat jelas.

Instruksi Analisis:
1. Jika di layar terlihat pengguna belum masuk (ada tombol 'Masuk' atau 'Daftar'), arahkan mereka untuk masuk/daftar terlebih dahulu agar dapat bertransaksi.
2. Jika terdapat kotak pencarian atau form filter, WAJIB arahkan pengguna dengan menyorot area tersebut secara keseluruhan.
3. Jika sedang melihat produk atau toko, berikan petunjuk langkah selanjutnya.
4. Jelaskan isi layar ini dengan detail, informatif, dan komunikatif. Pecah penjelasan menjadi 3-4 paragraf yang dipisahkan dengan baris baru ganda (\n\n). Gunakan format **Teks Tebal** untuk memberikan penekanan pada sub-judul poin.

PENTING: Anda HARUS mencocokkan niat pengguna (atau tindakan utama di layar) dengan salah satu ID Aksi berikut ini jika relevan:
- \"filter-area\": Seluruh baris form filter (Nama Bunga, Kategori, Kabupaten, dll). Jika ada, WAJIB gunakan label ini tepatnya: \"Gunakan area filter ini untuk mempermudah pencarian Anda. Ketikkan nama bunga, pilih kategori, atau tentukan lokasi (Kabupaten/Kecamatan) untuk menemukan toko bunga yang paling dekat dengan Anda.\"
- \"auth-area\": Grup tombol masuk dan daftar. Jika ada, WAJIB gunakan label ini tepatnya: \"Gunakan tombol Masuk atau Daftar untuk mengakses akun Anda dan menyimpan riwayat transaksi.\"
- \"input-search\": Kotak pencarian produk tunggal.
- \"input-category\": Filter kategori tunggal.
- \"input-location\": Filter wilayah/kabupaten.
- \"btn-search\": Tombol terapkan filter/cari.
- \"btn-order\": Tombol pesan sekarang / beli.

Wajib menjawab HANYA dalam format JSON dengan struktur yang valid:
{
  \"pesan\": \"Penjelasan panduan yang sangat detail untuk pengguna.\",
  \"tombol_penting\": [
    { 
      \"action_id\": \"PILIH_SALAH_SATU_ID_AKSI_DI_ATAS_JIKA_ADA\", 
      \"label\": \"Keterangan tombol ini buat apa\" 
    }
  ]
}
Catatan Penting: Identifikasi hingga 4 tindakan UTAMA. Jika tindakan tersebut cocok dengan ID Aksi di atas, isi `action_id`. Jika tidak ada yang cocok, Anda boleh mengosongkan action_id. Jangan menambahkan kata lain. Kembalikan JSON mentah.";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inlineData' => [
                                    'mimeType' => 'image/jpeg',
                                    'data' => $request->image_base64
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $resultText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                return response()->json(['result' => $resultText], 200);
            }

            if ($response->status() === 429) {
                return response()->json(['pesan' => 'Maaf, panduan AI sedang memproses terlalu banyak permintaan. Silakan tunggu beberapa detik dan coba lagi.'], 200);
            }

            return response()->json(['pesan' => 'Server AI gagal dihubungi.'], 500);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

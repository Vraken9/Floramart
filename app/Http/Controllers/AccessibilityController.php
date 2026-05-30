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
2. Jika terdapat kotak pencarian, arahkan pengguna untuk memanfaatkannya (misal: mencari nama bunga, alamat, kabupaten, kecamatan, atau kategori).
3. Jika sedang melihat produk atau toko, berikan petunjuk langkah selanjutnya (misal: 'klik tombol Pesan Sekarang untuk membeli' atau 'kunjungi toko untuk melihat koleksi lainnya').
4. Jelaskan isi layar ini dengan detail, informatif, dan komunikatif.

Wajib menjawab HANYA dalam format JSON dengan struktur yang valid:
{
  \"pesan\": \"Penjelasan dan panduan yang sangat detail untuk pengguna (jelas, santai, dan solutif, bisa 3-5 kalimat).\",
  \"tombol_penting\": [
    { \"teks\": \"Teks persis dari tombol/tautan 1\", \"label\": \"Fungsi (cth: Masuk Akun)\" },
    { \"teks\": \"Teks persis dari tombol/tautan 2\", \"label\": \"Fungsi (cth: Cari Alamat)\" }
  ]
}
Catatan: Identifikasi hingga 4 tombol/tautan/aksi utama yang relevan di layar. Jika tidak ada, biarkan array `tombol_penting` kosong. Jangan berikan penjelasan tambahan, cukup kembalikan JSON mentah.";

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

            return response()->json(['error' => 'Gagal menghubungi server AI: ' . $response->body()], 500);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

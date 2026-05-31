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

        $prompt = "Kamu adalah asisten panduan navigasi singkat untuk pengguna dengan kondisi buta warna " . $request->colorblind_type . ". Analisis UI pada gambar ini.

ATURAN FORMAT JAWABAN (WAJIB DIIKUTI):
1. Field \"pesan\" HARUS singkat: maksimal 2 kalimat pendek yang menjelaskan halaman apa ini dan apa yang bisa dilakukan pengguna. DILARANG menulis paragraf panjang atau penjelasan bertele-tele.
2. Semua detail fungsi tombol HARUS masuk ke array \"tombol_penting\" dengan label singkat (maksimal 15 kata per label).
3. Identifikasi maksimal 4 tombol/area penting yang terlihat di layar.

ID Aksi yang tersedia (gunakan jika cocok):
- \"filter-area\": Form filter/pencarian (Nama, Kategori, Kota, dll)
- \"auth-area\": Tombol Masuk & Daftar
- \"btn-order\": Tombol pesan/beli
- \"btn-search\": Tombol cari/terapkan filter
- \"input-search\": Kotak pencarian
- \"input-category\": Filter kategori
- \"input-location\": Filter wilayah
- \"btn-filter-mobile\": Tombol buka filter di mobile

Wajib jawab HANYA dalam JSON mentah (tanpa markdown):
{
  \"pesan\": \"Ringkasan singkat halaman ini (maks 2 kalimat).\",
  \"tombol_penting\": [
    { \"action_id\": \"ID_AKSI\", \"label\": \"Fungsi tombol ini (singkat)\" }
  ]
}";

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

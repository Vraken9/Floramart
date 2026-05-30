<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Category;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = $request->message;
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            return response()->json(['error' => 'API Key Gemini belum dikonfigurasi di server.'], 500);
        }

        // Ambil konteks dari database agar jawaban AI relevan
        // Mengambil kategori dan beberapa produk populer/terbaru sebagai referensi
        $categories = Category::pluck('name')->toArray();
        $products = Product::with('shop', 'category')->inRandomOrder()->limit(15)->get();
        
        $productContext = "Daftar beberapa produk yang tersedia di FloraMart saat ini:\n";
        foreach ($products as $p) {
            $productContext .= "- {$p->name} (Kategori: {$p->category->name}) - Harga: Rp" . number_format($p->price, 0, ',', '.') . " - Toko: {$p->shop->name}\n";
        }
        $categoryContext = "Kategori yang tersedia: " . implode(', ', $categories) . ".\n";

        $systemPrompt = "Kamu adalah FloraBot, asisten ahli bunga di FloraMart. 
PENTING: Jangan membuang waktu dengan sapaan panjang bertele-tele. Langsung berikan jawaban yang padat, singkat, dan tepat sasaran.

Konteks Toko:
$categoryContext
$productContext

Instruksi Mutlak:
1. JIKA pengguna mencari bunga untuk acara tertentu (hari ibu, wisuda, nikahan, dll), LANGSUNG BERIKAN 2-3 rekomendasi produk spesifik dari daftar di atas beserta harganya.
2. Jangan pernah menjawab hanya dengan basa-basi. Setiap jawaban HARUS mengandung nama produk atau solusi langsung.
3. Gunakan format markdown yang rapi (**bold** untuk nama produk/harga).
4. Maksimal 2 paragraf pendek.

Pertanyaan pengguna: \"$userMessage\"";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak bisa memproses permintaan Anda saat ini.';
                return response()->json(['reply' => $reply], 200);
            }

            return response()->json(['error' => 'Gagal menghubungi server AI.'], 500);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan sistem.'], 500);
        }
    }
}

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

        $systemPrompt = "Kamu adalah FloraBot, asisten cerdas untuk marketplace FloraMart. Aturan MUTLAK: 
1) DILARANG KERAS menggunakan sapaan basa-basi seperti 'Tentu, saya siap membantu' atau 'Halo!'. 
2) LANGSUNG berikan jawaban atau rekomendasi. 
3) Jawab dengan format poin-poin (bullet) yang sangat singkat, padat, dan jelas. 
4) Gunakan bahasa Indonesia yang santai tapi profesional. 
5) Maksimal berikan 3 rekomendasi bunga per jawaban.

Konteks Toko:
$categoryContext
$productContext

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

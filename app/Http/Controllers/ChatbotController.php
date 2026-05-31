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

        $systemPrompt = "Kamu adalah Asisten Florist Profesional di FloraMart.
ATURAN MUTLAK:

DILARANG KERAS menggunakan kalimat pembuka/basa-basi (Jangan pernah bilang 'Tentu', 'Halo', 'Saya siap membantu').

Kapanpun user meminta rekomendasi, LANGSUNG berikan 2-3 pilihan bunga teratas berdasarkan ketersediaan dan relevansi momen.

Gunakan gaya bahasa yang profesional, ramah, rasional, dan menarik. HINDARI gaya bahasa yang terlalu puitis, hiperbola (berlebihan/alay), atau terlalu romantis. Jawab dengan logis dan *to-the-point* namun tetap elegan.

Format wajib untuk setiap rekomendasi:
[Emoji] [Nama Bunga]
✨ Tampilan: [Deskripsikan bentuk, warna, atau kesegaran bunga secara realistis dan jelas].
💡 Alasan Memilih: [Berikan alasan logis, makna umum, atau kecocokan praktis mengapa bunga ini sangat pas untuk kebutuhan user].

Akhiri dengan satu kalimat Call-to-Action (CTA) singkat yang natural untuk mengarahkan pembelian.

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
                    'maxOutputTokens' => 10000,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak bisa memproses permintaan Anda saat ini.';
                return response()->json(['reply' => $reply], 200);
            }

            if ($response->status() === 429) {
                return response()->json(['error' => 'Maaf, server AI sedang sibuk (Rate Limit). Silakan tunggu sekitar 30 detik sebelum mencoba lagi.'], 429);
            }

            return response()->json(['error' => 'Gagal menghubungi server AI. Details: ' . $response->body()], 500);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan sistem.'], 500);
        }
    }
}

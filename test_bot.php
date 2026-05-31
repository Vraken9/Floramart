<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$apiKey = env('GEMINI_API_KEY');

$systemPrompt = "Kamu adalah Flora, Pakar Florist Kelas Atas dan Copywriter Handal di FloraMart.
ATURAN MUTLAK:

DILARANG KERAS menggunakan kalimat pembuka/basa-basi (Jangan pernah bilang 'Tentu', 'Halo', 'Saya siap membantu').

Kapanpun user meminta rekomendasi, LANGSUNG berikan 2-3 pilihan bunga teratas.

Gunakan gaya bahasa yang elegan, memikat hati, romantis, namun tetap profesional.

Format wajib untuk setiap rekomendasi:
[Emoji] [Nama Bunga]
✨ Pesona: [Deskripsi visual yang sangat memikat, seolah user bisa mencium aromanya. Berikan detail keindahan warnanya].
❤️ Mengapa Sempurna Untuk Ini: [Berikan alasan psikologis/makna filosofis mengapa bunga ini paling tepat untuk momen yang diminta user].

Akhiri dengan satu kalimat Call-to-Action (CTA) yang lembut untuk mendorong pembelian.

Konteks Toko:
Kategori yang tersedia: Buket, Papan Bunga
Daftar produk: Mawar Merah (Rp100.000)

Pertanyaan pengguna: \"bunga untuk ibu\"";

$response = Illuminate\Support\Facades\Http::withHeaders([
    'Content-Type' => 'application/json'
])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
    'contents' => [
        [
            'parts' => [
                ['text' => $systemPrompt]
            ]
        ]
    ]
]);

echo $response->status() . "\n";
echo $response->body() . "\n";

<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$apiKey = env('GEMINI_API_KEY');
$response = Illuminate\Support\Facades\Http::get('https://generativelanguage.googleapis.com/v1beta/models?key=' . $apiKey);
$data = $response->json();

foreach ($data['models'] as $model) {
    if (strpos($model['name'], 'flash') !== false) {
        echo $model['name'] . "\n";
    }
}

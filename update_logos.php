<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$shops = App\Models\Shop::all();
$files = scandir(public_path('images/shop_logos'));
$images = array_values(array_filter($files, function($f) { return str_ends_with($f, '.png') || str_ends_with($f, '.jpg'); }));

$i = 0;
foreach($shops as $shop) {
    if(isset($images[$i])) {
        $shop->logo_path = 'shop_logos/' . $images[$i];
        $shop->save();
        echo "Updated shop {$shop->id} with logo {$shop->logo_path}\n";
    }
    $i++;
    if($i >= count($images)) $i = 0; // loop if more shops than images
}

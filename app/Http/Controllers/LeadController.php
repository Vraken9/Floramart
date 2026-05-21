<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductLead;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function redirectWhatsApp($id)
    {
        $product = Product::with('shop')->findOrFail($id);

        // Track the lead
        ProductLead::insert([
            'product_id' => $product->id,
            'shop_id' => $product->shop_id,
            'user_id' => Auth::id(),
            'clicked_at' => now(),
        ]);

        // Format phone number to start with 62
        $phone = $product->shop->whatsapp_number;

        // Remove any non-numeric characters just in case
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 1) !== '6' && substr($phone, 0, 2) !== '62') {
             // In case they just put 812...
             $phone = '62' . $phone;
        }

        // Format the message
        $formattedPrice = number_format($product->price, 0, ',', '.');
        $message = "Halo {$product->shop->name}, saya tertarik untuk memesan bunga *{$product->name}* seharga Rp {$formattedPrice} yang saya lihat di FloraMart. Apakah masih bisa dipesan?";

        $url = 'https://wa.me/' . $phone . '?text=' . urlencode($message);

        return redirect()->away($url);
    }
}

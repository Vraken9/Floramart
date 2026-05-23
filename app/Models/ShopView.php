<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopView extends Model
{
    protected $fillable = [
        'shop_id',
        'user_id',
        'ip_address',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}

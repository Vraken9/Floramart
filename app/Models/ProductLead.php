<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLead extends Model
{
    protected $guarded = ['id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function leads()
    {
        return $this->hasMany(ProductLead::class);
    }
}

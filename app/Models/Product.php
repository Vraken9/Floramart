<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // TAMBAHKAN BARIS INI UNTUK MENGHILANGKAN ERROR:
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
    /**
     * Relasi untuk melihat siapa saja user yang memfavoritkan produk ini.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }
}

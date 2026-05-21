<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    // Relasi ke Toko (Satu kecamatan bisa punya banyak toko)
    public function shops()
    {
        return $this->hasMany(Shop::class);
    }
}

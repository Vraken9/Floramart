<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false; // Karena tabel wilayah biasanya tidak butuh created_at

    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }
}

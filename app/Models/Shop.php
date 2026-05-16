<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $guarded = ['id']; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function schedules()
    {
        return $this->hasMany(ShopSchedule::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

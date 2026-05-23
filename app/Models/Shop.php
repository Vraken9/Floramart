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

    public function getAverageRatingAttribute()
    {
        $products = $this->products()->withAvg('reviews', 'rating')->get();
        $avg = $products->avg('reviews_avg_rating');
        return round($avg, 1) ?: 0;
    }

    public function parentShop()
    {
        return $this->belongsTo(Shop::class, 'parent_shop_id');
    }

    public function branches()
    {
        return $this->hasMany(Shop::class, 'parent_shop_id');
    }

    public function productLeads()
    {
        return $this->hasMany(ProductLead::class);
    }

    public function shopViews()
    {
        return $this->hasMany(ShopView::class);
    }
}

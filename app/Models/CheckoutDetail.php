<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutDetail extends Model
{
    protected $fillable = [
        'quantity', 'price', 'sub_total', 'product_id', 'checkout_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function product() {
        return $this->belongsTo('App\Models\Product');
    }

    public function checkout() {
        return $this->belongsTo('App\Models\Checkout');
    }
}

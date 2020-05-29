<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'quantity', 'is_checkedout', 'product_id', 'user_id', 'store_id'
    ];

    protected $hidden = [
        'is_checkedout','updated_at'
    ];

    public function product() {
        return $this->belongsTo('App\Models\Product');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function store() {
        return $this->belongsTo('App\Models\Store');
    }
}

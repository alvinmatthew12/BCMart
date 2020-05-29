<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $fillable = [
        'total_price', 'user_id', 'store_id'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function store() {
        return $this->belongsTo('App\Models\Store');
    }
}

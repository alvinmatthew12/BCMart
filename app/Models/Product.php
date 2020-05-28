<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'barcode', 'store_id' 
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function store() {
        return $this->belongsTo('App\Models\Store');
    }
}

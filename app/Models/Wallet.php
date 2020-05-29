<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'balance', 'user_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTopUp extends Model
{
    protected $fillable = [
        'balance', 'bank_name', 'account_name', 'account_number', 'wallet_id'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function wallet() {
        return $this->belongsTo('App\Models\Wallet');
    }
}

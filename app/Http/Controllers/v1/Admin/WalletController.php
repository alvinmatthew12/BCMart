<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTopUp;
use App\Http\Requests\WalletRequest;
use Exception;

class WalletController extends Controller
{
    public function getTopUp() {
        // code for get TopUp
        // code for get TopUp
        $walletTopUps = WalletTopUp::with('wallet.user')->get();
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get all stores',
            'data' => $walletTopUps
        ], 200);
    }
}

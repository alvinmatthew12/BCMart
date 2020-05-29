<?php

namespace App\Http\Controllers\v1\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTopUp;
use App\Http\Requests\WalletRequest;
use Exception;

class WalletController extends Controller
{
    public function index()
    {
        try {
            $wallet = Wallet::with('user:id,name,email')->where('user_id', auth()->user()->id)->get();
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to find wallet',
                'data' => $data
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get wallet information',
            'data' => $wallet
        ]);
    }

    public function topUp(WalletRequest $request)
    {
        $data = $request->validated();
        try {
            $mywallet = Wallet::where('user_id', auth()->user()->id)->get();
            $data['wallet_id'] = $mywallet[0]['id'];
            $walletTopUp = WalletTopUp::create($data);

            $currentBalance = $mywallet[0]['balance'];
            $topUpBalance = $data['balance'];
            $calculateBalance = $currentBalance + $topUpBalance;

            $wallet = Wallet::with('user:id,name,email')->findOrFail($data['wallet_id']);
            $wallet->balance = $calculateBalance;
            $wallet->save();

            $walletTopUp['wallet'] = $wallet;
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to top up wallet balance',
                'data' => $data
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully top up wallet balance',
            'data' => $walletTopUp
        ], 200);
    }
}

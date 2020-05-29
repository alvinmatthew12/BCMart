<?php

namespace App\Http\Controllers\v1\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
// use App\Http\Requests\StoreRequest;
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

    public function update(Request $request, $id)
    {
        //
    }
}

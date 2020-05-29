<?php

namespace App\Http\Controllers\v1\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Store;
use App\Models\Checkout;
use App\Models\Wallet;
use App\Models\CheckoutDetail;
use App\Http\Requests\CheckoutRequest;
use Exception;

class CheckoutController extends Controller
{
    public function getCheckoutReport($id) {
        try {
            $store = Store::where([
                ['id', '=', $id],
                ['user_id', '=', auth()->user()->id],
            ])->get();
            
            if($store->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Sorry! You are unauthorized to make this request',
                    'data' => []
                ]);
            } else {
                $checkouts = Checkout::with('store')
                    ->where('store_id', $id)
                    ->get();
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to find checkout',
                'data' => $checkouts
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get checkout history',
            'data' => $checkouts
        ]);
    }

    public function show($id)
    {
        try {
            $checkout = Checkout::with('store')->findOrFail($id);

            $store = Store::where([
                ['id', '=', $checkout['store_id']],
                ['user_id', '=', auth()->user()->id],
            ])->get();
            
            if($store->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Sorry! You are unauthorized to make this request',
                    'data' => []
                ]);
            } else {
                $checkoutDetail = CheckoutDetail::with('product')
                    ->where('checkout_id', $checkout['id'])
                    ->get();
                $checkout['details'] = $checkoutDetail;
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to find checkout detail',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get checkout detail',
            'data' => $checkout
        ]);
    }
}

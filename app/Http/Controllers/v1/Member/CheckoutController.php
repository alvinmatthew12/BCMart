<?php

namespace App\Http\Controllers\v1\Member;

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
    public function index() {
        try {
            $checkouts = Checkout::with('store')
                ->where('user_id', auth()->user()->id)
                ->get();
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
            $checkoutDetail = CheckoutDetail::with('product')
                ->where('checkout_id', $checkout['id'])
                ->get();
            $checkout['details'] = $checkoutDetail;
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

    public function checkout(CheckoutRequest $request) {
        $data = $request->validated();
        try {
            $allCartItems = Cart::with('product','product.store')
                ->where([
                    ['is_checkedout', '=', 0],
                    ['user_id', '=', auth()->user()->id],
                ])
                ->get();

            $count = $allCartItems->count();
            if($count == 0){
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Failed to checkout! No cart item found',
                    'data' => []
                ], 400);
            }
            
            foreach($data['stores'] as $store) {
                $storeData = Store::findOrFail($store['id']);
                $cartItems = Cart::with('product','product.store')
                    ->where([
                        ['is_checkedout', '=', 0],
                        ['user_id', '=', auth()->user()->id],
                        ['store_id', '=', $store['id']],
                    ])
                    ->get();

                $checkoutTotalPrice = [];
                foreach($cartItems as $cart) {
                    $productSubTotal = $cart['product']['price'] * $cart['quantity'];
                    array_push($checkoutTotalPrice, $productSubTotal);
                }
                $totalPrice = array_sum($checkoutTotalPrice);

                $mywallet = Wallet::where('user_id', auth()->user()->id)->get();
                $currentBalance = $mywallet[0]['balance'];

                if($totalPrice > $currentBalance) {
                    return response()->json([
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'Insufficient wallet balance.',
                        'data' => []
                    ], 400);
                } else {
                    $calculateBalance = $currentBalance - $totalPrice;
                    $wallet = Wallet::findOrFail($mywallet[0]['id']);
                    $wallet->balance = $calculateBalance;
                    $wallet->save();

                    $checkout = Checkout::create([
                        'total_price' => $totalPrice,
                        'user_id' => auth()->user()->id,
                        'store_id' => $store['id'],
                    ]);
                    $checkoutId = $checkout->id;
                    foreach($cartItems as $cart) {
                        $detail = [
                            'quantity'=> $cart['quantity'],
                            'price' => $cart['product']['price'],
                            'sub_total' => $cart['product']['price'] * $cart['quantity'],
                            'product_id' => $cart['product_id'],
                            'checkout_id' => $checkoutId
                        ];
                        $checkoutDetail = CheckoutDetail::create($detail);
                        $ucart = Cart::findOrFail($cart['id']);
                        $ucart->is_checkedout = 1;
                        $ucart->save();
                    }
                }
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to checkout',
                'data' => $e
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully checkout',
            'data' => []
        ]);
    }
}

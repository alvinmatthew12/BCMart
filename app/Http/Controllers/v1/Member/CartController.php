<?php

namespace App\Http\Controllers\v1\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Store;
use App\Http\Requests\CartRequest;
use Exception;
use Validator;

class CartController extends Controller
{
    public function index()
    {
        try {
            $cart = Cart::with('product','product.store')
                ->where([
                    ['is_checkedout', '=', 0],
                    ['user_id', '=', auth()->user()->id]
                ])->get();
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to find cart',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get cart information',
            'data' => $cart
        ]);
    }

    public function store(CartRequest $request)
    {
        $data = $request->validated();
        try {
            $data['user_id'] = auth()->user()->id;

            $product = Product::where('barcode', $data['barcode'])->get();
            $data['product_id'] = $product[0]['id'];
            $data['store_id'] = $product[0]['store_id'];

            $checkCart = Cart::where([
                ['product_id', '=', $data['product_id']],
                ['is_checkedout', '=', 0]
            ])->get();

            if($checkCart->isEmpty()) {
                $cart = Cart::create($data);
            } else {
                $cart = Cart::findOrFail($checkCart[0]['id']);
                $calculateQty = $cart['quantity'] + $data['quantity'];
                $cart->quantity = $calculateQty;
                $cart->save();
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to add item to cart',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully add item to cart',
            'data' => []
        ], 200);
    }

    public function update(Request $request, $id)
    {
    	$validator = Validator::make($request->all(),[
            'quantity' => 'required|integer',
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        try {

            if($request->quantity != NULL){
                $cart = Cart::with('product','product.store')->findOrFail($id);
                $cart->quantity = $request->quantity;
                $cart->save();
            } else {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Sorry, at least quantity is bigger than 1',
                    'data' => []
                ], 400);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to update cart',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully update cart',
            'data' => $cart
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $cart->delete();
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to remove item from cart',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully remove item from cart',
            'data' => []
        ]);
    }
}

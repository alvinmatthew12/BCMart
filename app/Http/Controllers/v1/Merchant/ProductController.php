<?php

namespace App\Http\Controllers\v1\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Http\Requests\ProductRequest;
use Exception;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function getProducts(Request $request) 
    {
        $data = $request;
        try {
            $products = Product::where('store_id', $data['store_id'])->get();
            $validateStore = Store::where([
                ['id', '=', $data['store_id']],
                ['user_id', '=', auth()->user()->id]
            ])->get();
            if($validateStore->isEmpty()){
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Sorry! You are unauthorized to make this request',
                    'data' => []
                ]);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to get products',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get all products',
            'data' => $products
        ], 200);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        try {
            $validateStore = Store::where([
                ['id', '=', $data['store_id']],
                ['user_id', '=', auth()->user()->id]
            ])->get();
            if($validateStore->isEmpty()){
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Sorry! You are unauthorized to make this request',
                    'data' => []
                ]);
            } else {
                $barcode = $this->generateBarcode();
                $data['barcode'] = $barcode;
                $product = Product::create($data);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to add new product',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully add new product',
            'data' => $product
        ], 200);
    }

    public function show($id)
    {
        try {
            $product = Product::with('store:id,name')->findOrFail($id);
            $validateStore = Store::where([
                ['id', '=', $product['store_id']],
                ['user_id', '=', auth()->user()->id]
            ])->get();
            if($validateStore->isEmpty()){
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Sorry! You are unauthorized to make this request',
                    'data' => []
                ]);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to find product',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get product information',
            'data' => $product
        ]);
    }

    public function update(ProductRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $validateStore = Store::where([
                ['id', '=', $data['store_id']],
                ['user_id', '=', auth()->user()->id]
            ])->get();
            if($validateStore->isEmpty()){
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Sorry! You are unauthorized to make this request',
                    'data' => []
                ]);
            } else {
                $product = Product::with('store:id,name')->findOrFail($id);
                $validateProduct = Product::where([
                    ['id', '=', $product['id']],
                    ['store_id', '=', $data['store_id']]
                ])->get();
                if($validateProduct->isEmpty()){
                    return response()->json([
                        'status' => 'error',
                        'code' => 401,
                        'message' => 'Sorry! The product not belong to you store.',
                        'data' => []
                    ]);
                } else {
                    $product->update($data);
                }
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to update product',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully update product information',
            'data' => $product
        ]);
    }

    public function updateBarcode(Request $request, $id) {
        $data = $request;
        try {
            $validateStore = Store::where([
                ['id', '=', $data['store_id']],
                ['user_id', '=', auth()->user()->id]
            ])->get();
            if($validateStore->isEmpty()){
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Sorry! You are unauthorized to make this request',
                    'data' => []
                ]);
            } else {
                $product = Product::findOrFail($id);
                $validateProduct = Product::where([
                    ['id', '=', $product['id']],
                    ['store_id', '=', $data['store_id']]
                ])->get();
                if($validateProduct->isEmpty()){
                    return response()->json([
                        'status' => 'error',
                        'code' => 401,
                        'message' => 'Sorry! The product not belong to you store.',
                        'data' => []
                    ]);
                } else {
                    if($product) {
                        $barcode = $this->generateBarcode();
                        $product->barcode = $barcode;
                        $product->save();
                    }
                }
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to update product barcode',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully update product barcode',
            'data' => $product
        ]);

    }

    public function destroy(Request $request, $id)
    {
        $data = $request;
        try {
            $validateStore = Store::where([
                ['id', '=', $data['store_id']],
                ['user_id', '=', auth()->user()->id]
            ])->get();
            if($validateStore->isEmpty()){
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Sorry! You are unauthorized to make this request',
                    'data' => []
                ]);
            } else {
                $product = Product::findOrFail($id);
                $validateProduct = Product::where([
                    ['id', '=', $product['id']],
                    ['store_id', '=', $data['store_id']]
                ])->get();
                if($validateProduct->isEmpty()){
                    return response()->json([
                        'status' => 'error',
                        'code' => 401,
                        'message' => 'Sorry! The product not belong to you store.',
                        'data' => []
                    ]);
                } else {
                    $product->delete();
                }
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to delete product',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully delete product',
            'data' => []
        ]);
    }

    public function generateBarcode() {
        $barcode = Str::random(8);
        return strtoupper($barcode);
    }
}

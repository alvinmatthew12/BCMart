<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Exception;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('store:id,name')->get();
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
            $barcode = $this->generateBarcode();
            $data['barcode'] = $barcode;
            $product = Product::create($data);
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
            $product = Product::with('store:id,name')->findOrFail($id);
            $product->update($data);
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

    public function updateBarcode($id) {
        try {
            $product = Product::findOrFail($id);
            if($product) {
                $barcode = $this->generateBarcode();
                $product->barcode = $barcode;
                $product->save();
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

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
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

<?php

namespace App\Http\Controllers\v1\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Exception;

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
}

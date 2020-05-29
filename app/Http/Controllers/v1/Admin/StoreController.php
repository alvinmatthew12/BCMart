<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Http\Requests\StoreRequest;
use Exception;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('user:id,name,email')->get();
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get all stores',
            'data' => $stores
        ], 200);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        try {
            $data['user_id'] = auth()->user()->id;
            $store = Store::create($data);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to add new store',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully add new store',
            'data' => $store
        ], 200);
    }

    public function show($id)
    {
        try {
            $store = Store::with('user:id,name,email')->findOrFail($id);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to find store',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully get store information',
            'data' => $store
        ]);
    }

    public function update(StoreRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $store = Store::with('user:id,name,email')->findOrFail($id);
            $store->update($data);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to update store',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully update store information',
            'data' => $store
        ]);
    }

    public function destroy($id)
    {
        try {
            $store = Store::findOrFail($id);
            $store->delete();
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Failed to delete store',
                'data' => []
            ], 400);
        }
        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Successfully delete store',
            'data' => []
        ]);
    }
}

<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        $data = $request->validated();
        if(!auth()->attempt($request->all())){
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Email or password is not correct',
                'data' => []
            ], 401);
        }

        $role = auth()->user()->role->name;
        
        // Create Passport token
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        $data = [
            'user' => auth()->user(),
            'access_token' => $accessToken
        ];

        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Sucessfully login to the system',
            'data' => $data
        ], 200);
    }

    public function logout() {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json([
            'status' => 'ok',
            'code' => 200,
            'message' => 'Sucessfully logged out!',
            'data' => []
        ], 200);
    }
}

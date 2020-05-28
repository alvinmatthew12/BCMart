<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->role_id != 1){
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Sorry! You are unauthorized to make this request',
                'data' => []
            ]);
        }
        return $next($request);
    }
}

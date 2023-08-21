<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {
        if($permission){
            $user = JWTAuth::parseToken()->toUser();
            // dd($user->toArray());
            if (!$user) {
                if ($request->expectsJson()) {
                    return response()->json([
                        "status"    => false,
                        "message"   => "Unauthenticate",
                        "data"      => []
                    ], 200);
                    // return api_response("Unauthenticate", [], false);
                }
            }
            if ($user->can($permission)) {
                return response()->json([
                    "status"    => false,
                    "message"   => "You don't have permission to {$permission}",
                    "data"      => []
                ], 200);
            }
        }
        return $next($request);
    }
}

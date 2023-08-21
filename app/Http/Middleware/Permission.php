<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private $data = "";
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::guest()) {
            return redirect(config('constants.ahsec'));
        }
        
        if (! $request->user()->can($permission)) {
           $this->data = '<h1 style="text-align:center;">You Are Not Authorised</h1>
            <hr>
            ';
            die($this->data);
            return true;
        }
        return $next($request);
    }
}

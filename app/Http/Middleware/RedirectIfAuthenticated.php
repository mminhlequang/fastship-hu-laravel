<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if ($request->bearerToken() && !auth()->check()) {
            return response()->json(['status' => false, 'message' => __('INVALID_SIGNATURE')]);
        }
        if (Auth::guard($guard)->check()) {
            return redirect()->intended('/admin');
        }

        return $next($request);
    }
}

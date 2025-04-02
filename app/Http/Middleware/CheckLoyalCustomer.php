<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckLoyalCustomer
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
        // Check if the 'loyal_customer' guard is authenticated
        if (!Auth::guard('loyal_customer')->check()) {
            // Redirect to a specific route or send an error response
            return redirect(''); // You can change this to any route you want
        }

        return $next($request);
    }
}

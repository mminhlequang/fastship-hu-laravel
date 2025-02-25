<?php

namespace App\Http\Middleware;

use Closure;

class SetLocale
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
        $language = 'vi'; // default

        if (request('language') || session('language')) {
            $language = request('language') ?? session('language');
            session()->put('language', $language);
        }
        app()->setLocale($language);

        return $next($request);
    }
}

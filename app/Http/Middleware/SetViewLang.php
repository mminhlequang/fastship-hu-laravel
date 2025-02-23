<?php

namespace App\Http\Middleware;

use Closure;

class SetViewLang
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
        $lang = $request->route('lang');
        if (!empty($lang) && in_array($lang, config('app.locales'))){
            \View::composer('*', function ($view) use ($lang){
                $view->with(['language' => $lang]);
            });
        }

        return $next($request);
    }
}

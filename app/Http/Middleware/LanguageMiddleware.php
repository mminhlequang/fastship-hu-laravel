<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Lấy ngôn ngữ từ header 'Accept-Language'
        $language = $request->header('Accept-Language', 'vi'); // Mặc định là 'en'

        // Thiết lập ngôn ngữ cho ứng dụng
        App::setLocale($language);

        return $next($request);
    }
}


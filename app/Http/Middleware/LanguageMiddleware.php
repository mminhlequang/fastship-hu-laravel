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
        $language = $request->header('Accept-Language', 'vi'); // Default to 'vi'

        // Split the string by commas to handle the list of languages
        $languageList = explode(',', $language);

        // The first item in the list is the most preferred language
        $primaryLanguage = explode(';', $languageList[0])[0];

        // Set the application's locale
        App::setLocale($primaryLanguage);

        return $next($request);
    }
}


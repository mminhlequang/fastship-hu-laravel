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
        $language = $request->header('Accept-Language', 'en'); // Default to 'vi'

        // Split the string by commas to handle the list of languages
        $languageList = explode(',', $language);

        // The first item in the list is the most preferred language
        $primaryLanguage = explode(';', $languageList[0])[0];

        // Các ngôn ngữ hợp lệ
        $validLanguages = ['en', 'vi', 'zh', 'hu'];

        // Kiểm tra xem ngôn ngữ có hợp lệ hay không
        if (in_array($primaryLanguage, $validLanguages)) {
            // Nếu hợp lệ, thiết lập ngôn ngữ ứng dụng
            App::setLocale($primaryLanguage);
        } else {
            // Nếu không hợp lệ, thiết lập ngôn ngữ mặc định là 'vi'
            App::setLocale('vi');
        }

        return $next($request);
    }
}


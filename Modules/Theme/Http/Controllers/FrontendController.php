<?php

namespace Modules\Theme\Http\Controllers;


use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\News;


class FrontendController extends Controller
{
    public function __construct()
    {
        $settings = Setting::allConfigsKeyValue();

        \View::share([
            'settings' => $settings,
        ]);
    }


    public function index()
    {
        return view('theme::front-end.pages.home');
    }



    public function getListParents(Request $request, $slugParent)
    {
        return view("theme::front-end.404", compact('slugParent'));
    }


    public function getDetail($slugParent, $slugDetail, Request $request)
    {
        switch ($slugParent) {
            case "tin-tuc":
                $news = News::with(['category'])->where(['active' => config('settings.active'), ['slug', $slugDetail]])->first();
                $otherNews = News::with('category')->where([['active', '=', config('settings.active')], ['id', '<>', $news->id]])->orderByDesc('created_at')->take(3)->get();
                return view("theme::front-end.news.detail", compact('news', 'otherNews'));
            default:
                return view("theme::front-end.404", compact('slugParent', 'slugDetail'));
        }
    }

    public function getPage($slug, Request $request)
    {
        switch ($slug) {
            case "stripe":
                return view('theme::front-end.payment');
            case "dang-ky":
                return view('theme::front-end.pages.register');
            default:
                return view('theme::front-end.404');
        }

    }


}
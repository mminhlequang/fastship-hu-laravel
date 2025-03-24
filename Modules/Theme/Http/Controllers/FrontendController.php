<?php

namespace Modules\Theme\Http\Controllers;


use App\Models\Product;
use App\Models\Setting;
use App\Models\Store;
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


    public function index(Request $request)
    {
        $latitude = $_COOKIE['lat'] ?? "16.481734013476487";
        $longitude = $_COOKIE['lng'] ?? "107.60490258435505";
        $radius = 500;

        $categories = \DB::table('categories')->whereNull('parent_id')->whereNull('deleted_at')->orderBy(\DB::raw("SUBSTRING_INDEX(name_vi, ' ', -1)"), 'asc')->get();

        $storesQuery = Store::with('creator')->whereNull('deleted_at');

        $storesFaster = $storesQuery->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'desc')->take(4)->get();

        $storesFavorite = $storesQuery
            ->withCount('favorites') // Counting the number of favorites for each store
            ->orderBy('favorites_count', 'desc')->take(4)->get();

        $productsQuery = Product::with('store')->whereNull('deleted_at'); // Initialize the query

        $productsTopRate = $productsQuery
            ->withAvg('rating', 'star') // Calculate the average star rating for each store
            ->orderBy('rating_avg_star', 'desc')
            ->take(4)->get();

        $productsFavorite = $productsQuery
            ->withCount('favorites') // Counting the number of favorites for each store
            ->orderBy('favorites_count', 'desc')
            ->take(4)->get();

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
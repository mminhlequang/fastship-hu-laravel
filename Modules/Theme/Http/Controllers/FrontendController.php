<?php

namespace Modules\Theme\Http\Controllers;


use App\Models\Category;
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

        $popularCategories = Category::with('stores')->whereNull('parent_id')->whereNull('deleted_at')->orderBy('name_vi')->get();

        $storesQuery = Store::with('creator')->whereNull('deleted_at');


        $storesFavorite = $storesQuery
            ->withCount('favorites') // Counting the number of favorites for each store
            ->orderBy('favorites_count', 'desc')->take(4)->get();


        $productsQuery = Product::with('store')->whereHas('store', function ($query) {
            // Áp dụng điều kiện vào relation 'store'
            $query->where('active', 1); // Ví dụ điều kiện 'store' có trạng thái 'active'
        }); // Initialize the query

        $productFaster = $productsQuery->selectRaw(
            'products.*, ( 6371 * acos( cos( radians(?) ) * cos( radians( stores.lat ) ) * cos( radians( stores.lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( stores.lat ) ) ) ) AS distance',
            [$latitude, $longitude, $latitude]
        )
            ->join('stores', 'products.store_id', '=', 'stores.id')
            ->orderByRaw('distance', 'ASC')->take(4)->get();
        $productsTopRate = $productsQuery
            ->withAvg('rating', 'star') // Calculate the average star rating for each store
            ->orderBy('rating_avg_star', 'desc')
            ->take(4)->get();

        $productsFavorite = $productsQuery
            ->withCount('favorites') // Counting the number of favorites for each store
            ->orderBy('favorites_count', 'desc')
            ->take(4)->get();

        $news = News::where('active', 1)->latest()->take(3)->get();

        return view('theme::front-end.pages.home', compact('popularCategories', 'news', 'productFaster', 'storesFavorite', 'productsFavorite', 'productsTopRate'));
    }


    public function getListParents(Request $request, $slugParent)
    {
        switch ($slugParent) {
            case "become-our-driver":
                return view("theme::front-end.pages.become-our-driver");
            case "become-our-partner":
                return view("theme::front-end.pages.become-our-partner");
            case "become-our-customer":
                return view("theme::front-end.pages.become-our-customer");
            case "news":
                $news = News::where([['active', '=', config('settings.active')]])->latest()->get();
                return view("theme::front-end.pages.news", compact('news'));
            case "stores":
                $popularCategories = Category::with('stores')->whereNull('parent_id')->whereNull('deleted_at')->orderBy('name_vi')->get();
                return view("theme::front-end.pages.stores", compact('popularCategories'));
            case "store":
                return view("theme::front-end.pages.store");
            default:
                return view("theme::front-end.404", compact('slugParent', 'slugDetail'));
        }
    }


    public function getDetail($slugParent, $slugDetail, Request $request)
    {
        switch ($slugParent) {
            case "news":
                $news = News::where(['active' => config('settings.active'), ['slug', $slugDetail]])->first();
                $otherNews = News::where([['active', '=', config('settings.active')], ['id', '<>', $news->id]])->latest()->take(3)->get();
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
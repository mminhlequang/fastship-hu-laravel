<?php

namespace Modules\Theme\Http\Controllers;


use App\Models\Cart;
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
        $this->middleware(function ($request, $next) {
            $settings = Setting::allConfigsKeyValue();

            $categoriesFilter = \DB::table('categories')->whereNull('deleted_at')->orderBy('name_en')->pluck('name_en', 'id')->toArray();

            $userId = \Auth::guard('loyal_customer')->id(); // Bây giờ sẽ hoạt động

            $carts = Cart::has('cartItems')->with('cartItems')->where('user_id', $userId)->get();


            \View::share([
                'settings' => $settings,
                'categoriesFilter' => $categoriesFilter,
                'carts' => $carts,
            ]);

            return $next($request);
        });
    }

    public function changeLocale(Request $request)
    {
        $language = $request->get('language') ?? 'en';

        app()->setLocale($language);

        // Optionally, you can store the language preference in the session
        session(['language' => $language]);

        return redirect()->back();
    }


    public function index(Request $request)
    {

        $latitude = $_COOKIE['lat'] ?? "16.481734013476487";
        $longitude = $_COOKIE['lng'] ?? "107.60490258435505";

        $popularCategories = Category::with('stores')->whereNull('parent_id')->whereNull('deleted_at')->orderBy('name_vi')->get();

        $storesQuery = Store::with('creator')->whereNull('deleted_at');

        $categoriesChild = \DB::table('categories')->whereNull('deleted_at')->where('parent_id', $popularCategories->first()->id ?? 0)->orderBy('name_en')->get();

        $storesFavorite = $storesQuery
            ->withCount('favorites') // Counting the number of favorites for each store
            ->where('active', 1)
            ->orderBy('favorites_count', 'desc')->get();


        $productsQuery = Product::with('store')->whereHas('store', function ($query) {
            // Áp dụng điều kiện vào relation 'store'
            $query->where('active', 1); // Ví dụ điều kiện 'store' có trạng thái 'active'
        }); // Initialize the query

        // Calculate the distance and order by distance, taking the closest 4 products
        $productFaster = $productsQuery
            ->selectRaw(
                'products.*, 
        (6371 * acos(cos(radians(?)) * cos(radians(stores.lat)) * cos(radians(stores.lng) - radians(?)) + sin(radians(?)) * sin(radians(stores.lat)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->join('stores', 'products.store_id', '=', 'stores.id')
            ->orderByRaw('distance ASC')  // Order the results by the calculated distance
            ->take(4)  // Limit the results to the closest 4 products
            ->get();

        $productsTopRate = $productsQuery
            ->withAvg('rating', 'star') // Calculate the average star rating for each store
            ->orderBy('rating_avg_star', 'desc')
            ->take(4)->get();

        $productsFavorite = $productsQuery
            ->withCount('favorites') // Counting the number of favorites for each store
            ->orderBy('favorites_count', 'desc')
            ->take(4)->get();

        $news = News::where('active', 1)->latest()->take(3)->get();

        return view('theme::front-end.pages.home', compact('popularCategories', 'news', 'productFaster', 'storesFavorite', 'productsFavorite', 'productsTopRate', 'categoriesChild'));
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
            case "legal-policies":
                return view("theme::front-end.pages.policy");
            case "contact":
                return view("theme::front-end.pages.contact");
            case "faq":
                return view("theme::front-end.pages.faq");
            case "search-result":
                $popularCategories = Category::with('stores')->whereNull('parent_id')->whereNull('deleted_at')->orderBy('name_en')->select(['id', 'name_vi', 'name_en', 'name_zh', 'name_hu'])->take(5)->get();
                $productsQuery = Product::with('store')->whereHas('store', function ($query) {
                    // Áp dụng điều kiện vào relation 'store'
                    $query->where('active', 1); // Ví dụ điều kiện 'store' có trạng thái 'active'
                }); // Initialize the query
                $data = $productsQuery
                    ->withAvg('rating', 'star') // Calculate the average star rating for each store
                    ->orderBy('rating_avg_star', 'desc')
                    ->get();
                return view("theme::front-end.pages.foods", compact('data', 'popularCategories'));
            case "foods":
                $popularCategories = Category::with('stores')->whereNull('parent_id')->whereNull('deleted_at')->orderBy('name_en')->select(['id', 'name_vi', 'name_en', 'name_zh', 'name_hu'])->take(5)->get();
                $productsQuery = Product::with('store')->whereHas('store', function ($query) {
                    // Áp dụng điều kiện vào relation 'store'
                    $query->where('active', 1); // Ví dụ điều kiện 'store' có trạng thái 'active'
                }); // Initialize the query
                $data = $productsQuery
                    ->withAvg('rating', 'star') // Calculate the average star rating for each store
                    ->orderBy('rating_avg_star', 'desc')
                    ->get();
                return view("theme::front-end.pages.foods", compact('data', 'popularCategories'));
            case "search":
                //Get categories
                $categories = Category::with('children')->whereNull('parent_id')->whereNull('deleted_at')->orderBy('name_en')->select(['id', 'name_en'])->get();

                //1 Store, 2 Product
                $type = $request->type ?? 2;
                $minPrice = $request->min_price ?? '';
                $maxPrice = $request->max_price ?? '';
                $categoryIds = $request->categories ?? '';
                $keywords = $request->keywords ?? '';
                $sort = $request->sort ?? '';

                if ($type == 1) {
                    $storesQuery = Store::with('categories')->whereNull('deleted_at');
                    $data = $storesQuery
                        ->withCount('favorites') // Counting the number of favorites for each store
                        ->when($keywords ?? '', function ($query) use ($keywords) {
                            $query->where('name', 'like', "%$keywords%")->orWhere('address', 'like', "%$keywords%");
                        })->when($categoryIds != '', function ($query) use ($categoryIds) {
                            $query->whereHas('categories', function ($query) use ($categoryIds) {
                                $query->whereIn('category_id', explode(',', $categoryIds));
                            });
                        });

                    // Apply featured filter (based on the number of favorites)
                    if ($sort == 'recommended') {
                        $storesQuery->withCount('favorites') // Counting the number of favorites for each store
                        ->orderBy('favorites_count', 'desc'); // Sorting based on favorite count in descending order
                    }

                    if ($sort == 'rating') {
                        $storesQuery->withAvg('rating', 'star') // Calculate the average star rating for each store
                        ->orderBy('rating_avg_star', 'desc'); // Order by the average rating in ascending or descending order
                    }

                    if ($sort == 'distance' || $sort == 'deliveryTime' || $sort == 'deliveryPrice') {
                        $latitude = $_COOKIE['lat'] ?? 47.4989200;
                        $longitude = $_COOKIE['lng'] ?? 19.0671300;
                        $storesQuery->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
                            ->orderBy('distance', 'desc');
                    }


                    $data = $data->orderBy('favorites_count', 'desc')->get();
                } else {
                    $productsQuery = Product::with('store')->whereHas('store', function ($query) {
                        // Áp dụng điều kiện vào relation 'store'
                        $query->where('active', 1); // Ví dụ điều kiện 'store' có trạng thái 'active'
                    })->when($keywords ?? '', function ($query) use ($keywords) {
                        $query->where('name', 'like', "%$keywords%")->orWhere('description', 'like', "%$keywords%");
                    })->when($categoryIds != '', function ($query) use ($categoryIds) {
                        $query->whereHas('categories', function ($query) use ($categoryIds) {
                            $query->whereIn('category_id', explode(',', $categoryIds));
                        });
                    })->when($minPrice != '' & $maxPrice != '', function ($query) use ($minPrice, $maxPrice) {
                        $query->whereBetween('price', [$minPrice, $maxPrice]);
                    });


                    // Apply featured filter (based on the number of favorites)
                    if ($sort == 'recommended') {
                        $productsQuery->withCount('favorites') // Counting the number of favorites for each store
                        ->orderBy('favorites_count', 'desc'); // Sorting based on favorite count in descending order
                    }

                    if ($sort == 'rating') {
                        $productsQuery->withAvg('rating', 'star') // Calculate the average star rating for each store
                        ->orderBy('rating_avg_star', 'desc'); // Order by the average rating in ascending or descending order
                    }

                    if ($sort == 'distance' || $sort == 'deliveryTime' || $sort == 'deliveryPrice') {
                        $latitude = $_COOKIE['lat'] ?? 47.4989200;
                        $longitude = $_COOKIE['lng'] ?? 19.0671300;
                        $productsQuery->selectRaw(
                            'products.*, ( 6371 * acos( cos( radians(?) ) * cos( radians( stores.lat ) ) * cos( radians( stores.lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( stores.lat ) ) ) ) AS distance',
                            [$latitude, $longitude, $latitude]
                        )
                            ->join('stores', 'products.store_id', '=', 'stores.id')->orderByRaw('distance ' . strtoupper('desc'));;
                    }

                    // Initialize the query
                    $data = $productsQuery
                        ->withAvg('rating', 'star') // Calculate the average star rating for each store
                        ->orderBy('rating_avg_star', 'desc')
                        ->get();
                }

                return view("theme::front-end.pages.search", compact('data', 'categories', 'type'));
            case "blogs":
                $news = News::where([['active', '=', config('settings.active')]])->latest()->get();
                return view("theme::front-end.pages.news", compact('news'));
            case "stores":
                $popularCategories = Category::with('stores')->whereNull('parent_id')->whereNull('deleted_at')->orderBy('name_en')->get();
                $storesQuery = Store::with('creator')->whereNull('deleted_at');
                $data = $storesQuery
                    ->withCount('favorites') // Counting the number of favorites for each store
                    ->where('active', 1)
                    ->orderBy('favorites_count', 'desc')->get();

                return view("theme::front-end.pages.stores", compact('popularCategories', 'data'));
            default:
                return view("theme::front-end.404");
        }
    }


    public function getDetail($slugParent, $slugDetail, Request $request)
    {
        switch ($slugParent) {
            case 'store':
                $store = Store::with(['products', 'categories'])->where('slug', $slugDetail)->first();
                if (!$store) return view("theme::front-end.404");
                return view("theme::front-end.pages.store", compact('store'));
            case "blogs":
                $news = News::where(['active' => config('settings.active'), ['slug', $slugDetail]])->first();
                if (!$news) return view("theme::front-end.404");
                $otherNews = News::where([['active', '=', config('settings.active')], ['id', '<>', $news->id]])->latest()->take(3)->get();
                return view("theme::front-end.news.detail", compact('news', 'otherNews'));
            default:
                return view("theme::front-end.404");
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
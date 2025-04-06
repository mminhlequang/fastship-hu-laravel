<?php

namespace Modules\Theme\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AjaxFrontendController extends Controller
{

    /**
     * Gọi ajax: sẽ gọi đến hàm = tên $action
     * @param Request $action
     * @param Request $request
     * @return mixed
     */
    public function index($action, Request $request)
    {
        return $this->{$action}($request);
    }

    public function getDetailProduct(Request $request)
    {
        $id = $request->id;

        $product = Product::findOrFail($id);

        $view = view('theme::front-end.modals.product_inner', compact('product'))->render();

        return $view;
    }

    public function searchDataHome(Request $request)
    {
        $latitude = $_COOKIE['lat'] ?? "16.481734013476487";
        $longitude = $_COOKIE['lng'] ?? "107.60490258435505";
        $categories = $request->categories ?? '';

        $storesQuery = Store::with('creator')->whereNull('deleted_at');

        $categoriesChild = \DB::table('categories')->whereNull('deleted_at')->where('parent_id', $categories)->orderBy('name_en')->get();

        $storesFavorite = $storesQuery
            ->when($categories != '', function ($query) use ($categories) {
                $query->whereHas('categories', function ($query) use ($categories) {
                    $query->where('category_id', $categories);
                });
            })
            ->withCount('favorites') // Counting the number of favorites for each store
            ->orderBy('favorites_count', 'desc')->get();

        $productsQuery = Product::with('store')->whereHas('store', function ($query) {
            // Áp dụng điều kiện vào relation 'store'
            $query->where('active', 1); // Ví dụ điều kiện 'store' có trạng thái 'active'
        })->when($categories != '', function ($query) use ($categories) {
            $query->whereHas('categories', function ($query) use ($categories) {
                $query->where('category_id', $categories);
            });
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
        // Render từng view riêng biệt
        $view1 = view('theme::front-end.home.fastest', compact('productFaster'))->render();
        $view2 = view('theme::front-end.home.discount', compact('storesFavorite'))->render();
        $view3 = view('theme::front-end.home.discount_categories', compact('categoriesChild'))->render();

        // In your controller
        return response()->json([
            'status' => true,
            'view1' => $view1,
            'view2' => $view2,
            'view3' => $view3
        ]);
    }

    public function getStoreByCategory(Request $request){
        $categories = $request->categories ?? '';
        $storesQuery = Store::with('creator')->whereNull('deleted_at');

        $storesFavorite = $storesQuery
            ->when($categories != '', function ($query) use ($categories) {
                $query->whereHas('categories', function ($query) use ($categories) {
                    $query->where('category_id', $categories);
                });
            })
            ->withCount('favorites') // Counting the number of favorites for each store
            ->orderBy('favorites_count', 'desc')->get();

        // Render từng view riêng biệt
        $view = view('theme::front-end.home.discount', compact('storesFavorite'))->render();

        // In your controller
        return response()->json([
            'status' => true,
            'view' => $view
        ]);
    }

    public function selectDataFavorite(Request $request)
    {
        //1 Store, 2 Product
        $type = $request->type ?? 1;

        if ($type == 1) {
            $ids = \DB::table('stores_favorite')->where('user_id', \Auth::guard('loyal_customer')->id())->latest()->pluck('store_id')->toArray();

            $storesQuery = Store::with('creator')->whereNull('deleted_at');
            $data = $storesQuery->whereIn('id', $ids)->get();

        } else {
            $ids = \DB::table('products_favorite')->where('user_id', \Auth::guard('loyal_customer')->id())->latest()->pluck('product_id')->toArray();
            $productsQuery = Product::with('store')->whereNull('deleted_at');
            $data = $productsQuery->whereIn('id', $ids)->get();
        }
        // Render the view as HTML
        $view = ($type == 1) ? view('theme::front-end.ajax.stores', compact('data'))->render() : view('theme::front-end.ajax.products', compact('data'))->render();

        return $view;
    }

    public function searchData(Request $request)
    {
        //1 Store, 2 Product
        $type = $request->type ?? 1;
        $minPrice = $request->min_price ?? '';
        $maxPrice = $request->max_price ?? '';
        $categoryIds = $request->categories ?? '';
        $keywords = $request->keywords ?? '';
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
                })
                ->orderBy('favorites_count', 'desc')->get();
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
            }); // Initialize the query
            $data = $productsQuery
                ->withAvg('rating', 'star') // Calculate the average star rating for each store
                ->orderBy('rating_avg_star', 'desc')
                ->get();
        }
        // Render the view as HTML
        $view = ($type == 1) ? view('theme::front-end.ajax.stores', compact('data'))->render() : view('theme::front-end.ajax.products', compact('data'))->render();

        return $view;
    }

    public function loadChildCategoryWithProductsByUser(Request $request)
    {
        try {
            $categoryId = $request->id;
            $categories = Category::with('products')
                ->where('parent_id', $categoryId)->whereNull('deleted_at')->orderBy(\DB::raw("SUBSTRING_INDEX(name_vi, ' ', -1)"), 'asc')->get();

            return \response()->json([
                'status' => true,
                'view' => view('theme::front-end.pages.categories', compact('categories'))->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

    }

    public function loadChildCategoryWithProductsByStore(Request $request)
    {
        try {
            $storeId = $request->store_id ?? 0;
            $categoryId = $request->id;
            $categories = Category::with('products')
                ->when($storeId != 0, function ($query) use ($storeId) {
                    $query->whereHas('stores', function ($query) use ($storeId) {
                        $query->where('store_id', $storeId);
                    });
                    $query->join('categories_stores', 'categories.id', '=', 'categories_stores.category_id')
                        ->select('categories.*') // Select all fields from the categories table
                        ->orderBy('categories_stores.arrange');
                })->where('parent_id', $categoryId)->whereNull('deleted_at')->orderBy(\DB::raw("SUBSTRING_INDEX(name_vi, ' ', -1)"), 'asc')->get();

            return \response()->json([
                'status' => true,
                'view' => view('theme::front-end.pages.categories', compact('categories'))->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function favoriteProduct(Request $request)
    {
        try {
            // Check if the product is already favorited by the user
            $isFavorite = \DB::table('products_favorite')
                ->where('product_id', $request->id)
                ->where('user_id', auth('api')->id())
                ->exists();

            // If not favorited, insert into the database
            if (!$isFavorite) {
                \DB::table('products_favorite')->insert([
                    'product_id' => $request->id,
                    'user_id' => auth('api')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(null, __('errors.PRODUCT_FAVORITE_ADD'));
            } else {
                \DB::table('products_favorite')
                    ->where('product_id', $request->id)
                    ->where('user_id', auth('api')->id())
                    ->delete();
                return $this->sendResponse(null, __('errors.PRODUCT_FAVORITE_REMOVE'));
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function favoriteStore(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id' => 'required|exists:stores,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            // Check if the product is already favorited by the user
            $isFavorite = \DB::table('stores_favorite')
                ->where('store_id', $request->id)
                ->where('user_id', auth('api')->id())
                ->exists();

            // If not favorited, insert into the database
            if (!$isFavorite) {
                \DB::table('stores_favorite')->insert([
                    'store_id' => $request->id,
                    'user_id' => auth('api')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(null, __('STORE_FAVORITE_ADD'));
            } else {
                \DB::table('stores_favorite')
                    ->where('store_id', $request->id)
                    ->where('user_id', auth('api')->id())
                    ->delete();
                return $this->sendResponse(null, __('STORE_FAVORITE_REMOVE'));
            }

        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    private function sendResponse($result, $message)
    {
        $response = [
            'status' => true,
            'data' => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    private function sendError($error, $errorMessages = [], $code = 200, $result = null)
    {
        $response = [
            'status' => false,
            'message' => $error,
            'data' => $result
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

}

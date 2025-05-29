<?php

namespace Modules\Theme\Http\Controllers;

use App\Helper\HereHelper;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\Topping;
use App\Models\VariationValue;
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

    public function updateCart(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'id' => 'required|exists:cart_items,id',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => join(PHP_EOL, $validator->errors()->all())
            ]);
        }
        try {
            // Start database transaction
            \DB::beginTransaction();

            $cartItemId = $request->id;
            $quantity = $request->quantity ?? 1; // Default to 1 if quantity is not provided
            $cartItem = CartItem::with(['cart', 'productR'])->find($cartItemId);

            // Delete the cart item if the quantity is less than or equal to 0
            if ($quantity <= 0) {
                $cartItem->delete();
                \DB::commit();
                return $this->loadCart();
            }

            $product = $cartItem->productR;
            $price = $product->price * $quantity;

            // Handle variations
            if ($cartItem->variations != null) {
                foreach ($cartItem->variations as $variation) {
                    $price += $variation['price'];
                }
            }

            // Update the cart item with new values
            $cartItem->updateOrCreate(
                [
                    'product_id' => $cartItem->product_id, // Ensure this product is in the cart
                    'cart_id' => $cartItem->cart_id, // Ensure this is the correct cart
                ],
                [
                    'quantity' => $quantity,
                    'price' => $price,
                ]
            );

            // Commit the transaction
            \DB::commit();

            // Reload the cart with updated data
            return $this->loadCart();
        } catch (\Exception $e) {
            // Rollback in case of any error
            \DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Failed to update cart. Please try again.',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function loadCart($message = 'Cart updated successfully')
    {
        $carts = Cart::has('cartItems')->with('cartItems')->where('user_id', \Auth::guard('loyal_customer')->id())->get();

        $view = view('theme::front-end.ajax.cart', compact('carts'))->render();

        return response()->json([
            'status' => true,
            'view' => $view,
            'message' => $message
        ]);
    }


    public function deleteCart(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make(
            $requestData,
            [
                'id' => 'required|exists:cart_items,id',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => join(PHP_EOL, $validator->errors()->all())
            ]);
        }
        try {
            $id = $request->id;
            // Tìm cart item theo ID
            $cartItem = CartItem::find($id);
            // Xóa cart item
            $cartItem->delete();

            // Reload the cart with updated data
            return $this->loadCart('Delete cart successfully');

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function previewCalculate(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'store_id' => 'required|exists:stores,id',
                'ship_fee' => 'nullable|numeric',
                'tip' => 'nullable|numeric',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => join(PHP_EOL, $validator->errors()->all())
            ]);
        }
        try {
            $type = $request->type ?? 'pickup';
            $lat = $request->lat ?? $_COOKIE['lat'] ?? '';
            $lng = $request->lng ?? $_COOKIE['lng'] ?? '';
            $store_id = $request->store_id ?? '';
            $courierTip = $request->tip ?? 0;
            $shipFee = $request->ship_fee ?? 0;
            $distance = 0;
            $timeMinus = 0;
            $shipPolyline = null;
            $shipHereRaw = null;
            $userId = \Auth::guard('loyal_customer')->id();

            // Get the carts with the cart items, apply store filtering, and handle pagination
            $carts = Cart::has('cartItems')->with('cartItems')
                ->when($store_id != '', function ($query) use ($store_id) {
                    $query->where('store_id', $store_id);
                })
                ->where('user_id', $userId)
                ->get();

            // Initialize the cart items for total calculation
            $cartItems = $carts->flatMap(function ($cart) {
                return $cart->cartItems;
            });

            //Calculator shipFee if lat lng
            if ($lat != '' && $lng != '' && $type != 'pickup') {
                $store = \DB::table('stores')->where('id', $store_id)->select(['lat', 'lng'])->first();

                $route = HereHelper::getRoute(
                    $store->lat,
                    $store->lng,
                    $lat,
                    $lng
                );
                $fee = HereHelper::calculateShippingFee($route['distance']);

                $shipFee = round($fee, 2) ?? 0;
                $distance = round(($route['distance'] ?? 0) / 1000, 2);
                $timeMinus = $route['duration'] ?? 0;
                $shipPolyline = $route['polyline'] ?? null;
                $shipHereRaw = ($route['raw'] != null) ? json_encode($route['raw'], true) : null;
            }

            $subtotal = $cartItems->sum('price');
            $discount = $request->discount ?? 0;
            $applicationFee = $subtotal * 0.03;
            $total = $subtotal + $courierTip + $shipFee + $applicationFee - $discount;

            $view = view('theme::front-end.ajax.cart_summary', compact('subtotal', 'total', 'discount', 'shipFee', 'applicationFee', 'courierTip', 'carts'))->render();

            return response()->json([
                'status' => true,
                'view' => $view,
                'distance' => $distance,
                'time' => $timeMinus . ' min',
                'fee' => $shipFee,
                'raw' => $shipHereRaw,
                'line' => $shipPolyline,
                'text' => ('(' . $timeMinus . ' min' . ', ' . $distance . ' km)'),
                'message' => 'Get total successfully'
            ]);
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

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
            ->where('active', 1)
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

    public function getOrderStatus(Request $request)
    {
        $requestData = $request->all();

        $validator = \Validator::make(
            $requestData,
            [
                'id' => 'required|exists:orders,id',
            ]
        );
        if ($validator->fails())
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);

        \DB::beginTransaction();
        try {
            $id = $request->id;
            $storeStatus = $request->store_status ?? '';
            $processStatus = $request->process_status ?? '';
            $isDriver = $request->is_driver ?? '';

            $order = Order::find($id);

            if ($storeStatus != '' || $processStatus == 'completed') {
                $order->update([
                    'process_status' => $processStatus,
                    'store_status' => $storeStatus
                ]);
                $order->refresh();
            }

            // Render từng view riêng biệt
            $view1 = view('theme::front-end.ajax.order_status', compact('order'))->render();

            $view2 = '';
            $view3 = '';

            if (($order->driver_id != null && $order->process_status != 'completed') || $isDriver == 1)
                $view3 = view('theme::front-end.ajax.order_driver', compact('order'))->render();

            if ($order->process_status == 'completed' || $processStatus == 'completed')
                $view2 = view('theme::front-end.ajax.order_completed', compact('order'))->render();
            elseif ($storeStatus == 'completed' || $order->store_status == 'completed')
                $view2 = view('theme::front-end.ajax.order_store_completed', compact('order'))->render();
            elseif ($order->process_status == 'cancelled')
                $view2 = view('theme::front-end.ajax.order_cancel', compact('order'))->render();

            return response()->json([
                'status' => true,
                'view1' => $view1,
                'view2' => $view2,
                'view3' => $view3,
                'data' => $order
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

    }

    public function getProductsByStore(Request $request)
    {
        $storeId = $request->store_id;
        $keywords = $request->keywords ?? '';

        $store = Store::with(['categories.products' => function ($query) use ($keywords) {
            if ($keywords) {
                $query->where('name', 'like', '%' . $keywords . '%');
            }
        }])->findOrFail($storeId);

        $view = view('theme::front-end.ajax.products_by_store', compact('store'))->render();
        return $view;
    }

    public function getStoreByCategory(Request $request)
    {
        $categories = $request->categories ?? '';
        $storesQuery = Store::with('creator')->whereNull('deleted_at');

        $storesFavorite = $storesQuery
            ->when($categories != '', function ($query) use ($categories) {
                $query->whereHas('categories', function ($query) use ($categories) {
                    $query->where('category_id', $categories);
                });
            })
            ->withCount('favorites') // Counting the number of favorites for each store
            ->where('active', 1)
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

            $storesQuery = Store::with('creator')->where('active', 1)->whereNull('deleted_at');
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
                ->where('active', 1)
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


    public function removeFavorite(Request $request)
    {
        try {
            $id = $request->id;

            \DB::table('products_favorite')
                ->where('product_id', $id)
                ->where('user_id', \Auth::guard('loyal_customer')->id())
                ->delete();

            $ids = \DB::table('products_favorite')->where('user_id', \Auth::guard('loyal_customer')->id())->latest()->pluck('product_id')->toArray();
            $products = \App\Models\Product::whereIn('id', $ids)->whereNull('deleted_at')->select(['id', 'name', 'image', 'price'])->get();

            return response()->json([
                'status' => true,
                'view' => view('theme::front-end.dropdown.favorites_inner', compact('products'))->render(),
                'message' => 'Remove favorite successfully'
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
                ->where('user_id', \Auth::guard('loyal_customer')->id())
                ->exists();

            // If not favorited, insert into the database
            if (!$isFavorite) {
                \DB::table('products_favorite')->insert([
                    'product_id' => $request->id,
                    'user_id' => \Auth::guard('loyal_customer')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(1, __('PRODUCT_FAVORITE_ADD'));
            } else {
                \DB::table('products_favorite')
                    ->where('product_id', $request->id)
                    ->where('user_id', \Auth::guard('loyal_customer')->id())
                    ->delete();
                return $this->sendResponse(0, __('PRODUCT_FAVORITE_REMOVE'));
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
                ->where('user_id', \Auth::guard('loyal_customer')->id())
                ->exists();

            // If not favorited, insert into the database
            if (!$isFavorite) {
                \DB::table('stores_favorite')->insert([
                    'store_id' => $request->id,
                    'user_id' => \Auth::guard('loyal_customer')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(1, __('STORE_FAVORITE_ADD'));
            } else {
                \DB::table('stores_favorite')
                    ->where('store_id', $request->id)
                    ->where('user_id', \Auth::guard('loyal_customer')->id())
                    ->delete();
                return $this->sendResponse(0, __('STORE_FAVORITE_REMOVE'));
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

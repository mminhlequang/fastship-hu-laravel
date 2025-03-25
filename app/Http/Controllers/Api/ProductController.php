<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductRatingResource;
use App\Http\Resources\ProductResource;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\ProductRatingReply;
use Illuminate\Http\Request;
use Validator;


class ProductController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/product/get_products",
     *     tags={"Product"},
     *     summary="Get all product",
     *     @OA\Parameter(
     *         name="keywords",
     *         in="query",
     *         description="keywords",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Id store",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         description="min_price",
     *         required=false,
     *         @OA\Schema(type="double")
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         description="max_price",
     *         required=false,
     *         @OA\Schema(type="double")
     *     ),
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         description="lat",
     *         required=false,
     *         @OA\Schema(type="double")
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         description="lng",
     *         required=false,
     *         @OA\Schema(type="double")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         description="Radius",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="rate",
     *         in="query",
     *         description="rate",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_favorite",
     *         in="query",
     *         description="is_favorite",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_popular",
     *         in="query",
     *         description="is_popular",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_topseller",
     *         in="query",
     *         description="is_topseller",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="category_ids",
     *         in="query",
     *         description="category_ids(1,2,3)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_distance",
     *         in="query",
     *         description="sort_distance(asc,desc)",
     *         required=false,
     *         example="desc",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_distance",
     *         in="query",
     *         description="sort_distance(asc,desc)",
     *         required=false,
     *         example="desc",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get all products"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getProducts(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';
        $storeId = $request->store_id ?? '';
        $categoryIds = $request->category_ids ?? '';
        $latitude = $request->lat ?? '';
        $longitude = $request->lng ?? '';
        $minPrice = $request->min_price ?? '';
        $maxPrice = $request->max_price ?? '';
        $radius = $request->radius ?? '';
        $rate = $request->rate ?? '';
        $isFavorite = $request->is_favorite ?? null;
        $isPopular = $request->is_popular ?? null;
        $isTopSeller = $request->is_topseller ?? null;
        $sortRate = $request->sort_rate ?? 'desc'; // Default to 'desc'
        $sortDistance = $request->sort_distance ?? 'asc'; // Default to 'asc'

        try {
            $productsQuery = Product::with('store'); // Initialize the query

            // Apply keyword search
            if ($keywords != '') {
                $productsQuery->where('name_vi', 'like', "%$keywords%");
            }

            // Apply store_id search
            if ($storeId != '') {
                $productsQuery->where('store_id', $storeId);
            }

            // Apply price search
            if ($minPrice != '' && $maxPrice != '') {
                $productsQuery->whereBetween('price', [$minPrice, $maxPrice]);
            }

            // Apply category filter
            if ($categoryIds != '') {
                $categoryIdsArray = explode(',', $categoryIds);
                $productsQuery->whereHas('categories', function ($query) use ($categoryIdsArray) {
                    $query->whereIn('category_id', $categoryIdsArray);
                }); // Assuming products have category_id field
            }

            // Apply rating filter (if provided)
            if ($rate != '') {
                $productsQuery->whereHas('rating', function ($query) use ($rate) {
                    $query->havingRaw('AVG(star) >= ?', [$rate]);
                });
            }

            // Apply favorite filter (if user is logged in and favorite is requested)
            if ($isFavorite && auth('api')->id() != null) {
                $userId = auth('api')->id();
                $productsQuery->whereHas('favorites', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                });
            }

            // Apply popular filter (based on the number of orders)
            if ($isTopSeller !== null) {
                $productsQuery->withCount('orders') // Counting the number of orders for each store
                ->orderBy('orders_count', 'desc'); // Sorting based on order count in descending order
            }

            // Apply featured filter (based on the number of favorites)
            if ($isPopular !== null) {
                $productsQuery->withCount('favorites') // Counting the number of favorites for each store
                ->orderBy('favorites_count', 'desc'); // Sorting based on favorite count in descending order
            }

            // Apply distance filter (if lat, lng, and radius are provided)
            if ($latitude && $longitude && $radius) {
                $productsQuery->selectRaw(
                    'products.*, ( 6371 * acos( cos( radians(?) ) * cos( radians( stores.lat ) ) * cos( radians( stores.lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( stores.lat ) ) ) ) AS distance',
                    [$latitude, $longitude, $latitude]
                )
                    ->join('stores', 'products.store_id', '=', 'stores.id'); // Join with the stores table
            }

            // Sorting by rate (if specified)
            if ($rate != '' && $sortRate) {
                $productsQuery->withAvg('rating', 'star') // Calculate the average star rating for each store
                ->orderBy('rating_avg_star', $sortRate); // Order by the average rating in ascending or descending order
            }

            // Sorting by distance (if applicable)
            if ($latitude && $longitude && $radius && $sortDistance) {
                $productsQuery->orderByRaw('distance ' . strtoupper($sortDistance)); // Order by distance, in ascending/descending order
            }

            // Pagination with limit and offset
            $products = $productsQuery->whereNull('deleted_at')->skip($offset)->take($limit)->get();

            return $this->sendResponse(ProductResource::collection($products), __('GET_PRODUCTS_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product/detail",
     *     tags={"Product"},
     *     summary="Get detail product by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function detail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id' => 'required|exists:products,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $data = Product::find($requestData['id']);

            return $this->sendResponse(new ProductResource($data), "Get detail successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product/get_favorites",
     *     tags={"Product"},
     *     summary="Get all product favorite by user",
     *     @OA\Parameter(
     *         name="keywords",
     *         in="query",
     *         description="keywords",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get all products"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getListFavoriteByUser(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';

        try {
            $ids = \DB::table('products_favorite')->where('user_id', auth('api')->id())->pluck('product_id')->toArray();

            $data = Product::with('store')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            });

            $data = $data->whereIn('id', $ids)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(ProductResource::collection($data), 'Get all products successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product/get_ratings",
     *     tags={"Product"},
     *     summary="Get all rating product",
     *     @OA\Parameter(
     *         name="product_id",
     *         in="query",
     *         description="product_id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="(1:30 ngày, 2:7 ngày, 3:Tất cả)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="star",
     *         in="query",
     *         description="star",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="(1:Bình luận, 2:Hình ảnh,Video, 3:Tất cả)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get all rating"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getListRating(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'product_id' => 'required|exists:products,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        //1:30 ngày trước, 2:7 ngày trước, 3:Tất cả
        $date = $request->date ?? 3;
        $star = $request->star ?? '';
        //1:Bình luận, 2:Hình ảnh,Video, 3:Tất cả)
        $type = $request->type ?? 3;

        try {

            $data = ProductRating::with('user')
                ->when($star != '', function ($query) use ($star) {
                    $query->where('star', $star);
                })
                ->when($date != 3, function ($query) use ($date) {
                    if ($date == 1) {
                        // Filter ratings from the last 30 days
                        $query->where('created_at', '>=', now()->subDays(30));
                    } elseif ($date == 2) {
                        // Filter ratings from the last 7 days
                        $query->where('created_at', '>=', now()->subDays(7));
                    }
                })
                ->when($type != 3, function ($query) use ($type) {
                    $query->whereHas('images', function ($query) use ($type) {
                        if ($type == 2)
                            $query->whereNotNull('id');
                        else
                            $query->whereNull('id');
                    });

                })
                ->where('product_id', $request->product_id)
                ->latest()
                ->skip($offset)
                ->take($limit);

            //Get the average rating and count of ratings
            $averageRating = $data->avg('star'); // average of 'star' field
            $ratingCount = $data->count('id'); // count of ratings

            //Now, get the paginated data
            $data = $data->get();

            return $this->sendResponse([
                'rating_average' => doubleval($averageRating),
                'rating_count' => intval($ratingCount),
                'data' => ProductRatingResource::collection($data)
            ], 'Get all rating successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/product/create",
     *     tags={"Product"},
     *     summary="Create product",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="name_vi", type="string", example="0964541340"),
     *             @OA\Property(property="name_en", type="string", example="0964541340"),
     *             @OA\Property(property="name_zh", type="string", example="0964541340"),
     *             @OA\Property(property="name_hu", type="string", example="0964541340"),
     *             @OA\Property(property="price", type="double", example="123456"),
     *             @OA\Property(property="image", type="string", example="abcd"),
     *             @OA\Property(property="description", type="string", example="Mô tả"),
     *             @OA\Property(property="status", type="integer", example="1", description="1:Hiện, 0:Ẩn"),
     *             @OA\Property(property="store_id", type="integer", example="1"),
     *             @OA\Property(
     *                  property="operating_hours",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="day", type="integer", example=1, description="Day of the week (1 = Monday, 2 = Tuesday, ..., 7 = Sunday)"),
     *                      @OA\Property(
     *                       property="hours",
     *                      type="array",
     *                      @OA\Items(type="string", example="09:00", description="Operating hours for the day"),
     *                      example={"09:00", "18:00"},
     *                      description="Operating hours for the day"
     *                      )
     *              ),
     *              example={
     *                  {"day": 1, "hours": {"09:00", "18:00"}},
     *                  {"day": 2, "hours": {"09:00", "18:00"}},
     *                  {"day": 3, "hours": {"09:00", "18:00"}},
     *                  {"day": 4, "hours": {"09:00", "18:00"}},
     *                  {"day": 5, "hours": {"09:00", "18:00"}},
     *                  {"day": 6, "hours": {"10:00", "15:00"}},
     *                  {"day": 7, "hours": {"10:00", "15:00"}}
     *              },
     *              description="Operating hours for each day of the week"
     *             ),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function create(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'name_vi' => 'required|min:5|max:120',
                'name_en' => 'required|min:5|max:120',
                'name_zh' => 'required|min:5|max:120',
                'name_hu' => 'required|min:5|max:120',
                'status' => 'nullable|in:0,1',
                'store_id' => 'required|exists:stores,id',
            ],
            [
                'name_vi.required' => 'Tên sản phẩm bắt buộc phải có',
                'name_vi.min' => 'Tên sản phẩm tối thiểu 5 kí tự',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Product::uploadAndResize($request->file('image'));

            $requestData['creator_id'] = auth('api')->id();

            $data = Product::create($requestData);

            if (!empty($request->operating_hours)) {
                // Cập nhật giờ hoạt động
                $hoursData = $request->operating_hours;
                $data->updateStoreHours($hoursData);
            }

            \DB::commit();
            return $this->sendResponse(new ProductResource($data), __('PRODUCT_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/product/update",
     *     tags={"Product"},
     *     summary="Update product",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Club object that needs to be update",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="name_vi", type="string", example="name vi"),
     *             @OA\Property(property="name_en", type="string", example="name en"),
     *             @OA\Property(property="name_zh", type="string", example="name zh"),
     *             @OA\Property(property="name_hu", type="string", example="name hu"),
     *             @OA\Property(property="price", type="double", example="50000"),
     *             @OA\Property(property="image", type="string", example="abcd"),
     *             @OA\Property(property="description", type="string", example="Mô tả"),
     *             @OA\Property(property="status", type="integer", example="1", description="1:Hiện, 0:Ẩn"),
     *             @OA\Property(property="store_id", type="integer", example="1"),
     *             @OA\Property(
     *                  property="operating_hours",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="day", type="integer", example=1, description="Day of the week (1 = Monday, 2 = Tuesday, ..., 7 = Sunday)"),
     *                      @OA\Property(
     *                       property="hours",
     *                      type="array",
     *                      @OA\Items(type="string", example="09:00", description="Operating hours for the day"),
     *                      example={"09:00", "18:00"},
     *                      description="Operating hours for the day"
     *                      )
     *              ),
     *              example={
     *                  {"day": 1, "hours": {"09:00", "18:00"}},
     *                  {"day": 2, "hours": {"09:00", "18:00"}},
     *                  {"day": 3, "hours": {"09:00", "18:00"}},
     *                  {"day": 4, "hours": {"09:00", "18:00"}},
     *                  {"day": 5, "hours": {"09:00", "18:00"}},
     *                  {"day": 6, "hours": {"10:00", "15:00"}},
     *                  {"day": 7, "hours": {"10:00", "15:00"}}
     *              },
     *              description="Operating hours for each day of the week"
     *             ),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update club Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:products,id',
                'name_vi' => 'required|min:5|max:120',
                'name_en' => 'required|min:5|max:120',
                'name_zh' => 'required|min:5|max:120',
                'name_hu' => 'required|min:5|max:120',
                'description' => 'nullable|max:3000',
                'active' => 'nullable|in:0,1',
                'store_id' => 'required|exists:stores,id',
                'time_open' => 'nullable|date_format:Y-m-d H:i',
                'time_close' => 'nullable|date_format:Y-m-d H:i|after:time_open',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            if ($request->time_open != null && $request->time_open != null) $requestData['status'] = 0;

            $data = Product::find($requestData['id']);

            $data->update($requestData);

            $data->refresh();

            if (!empty($request->operating_hours)) {
                // Cập nhật giờ hoạt động
                $hoursData = $request->operating_hours;
                $data->updateStoreHours($hoursData);
            }
            \DB::commit();
            return $this->sendResponse(new ProductResource($data), __('errors.PRODUCT_UPDATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/product/delete",
     *     tags={"Product"},
     *     summary="Delete product",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete product",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Delete successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function delete(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id' => 'required|exists:products,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            \DB::table('products')->where('id', $request->id)->update([
                'deleted_at' => now()
            ]);
            return $this->sendResponse(null, __('errors.PRODUCT_DELETED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/product/favorite/insert",
     *     tags={"Product"},
     *     summary="Favorite product",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Favorite product",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favorite successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function insertFavorite(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id' => 'required|exists:products,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

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
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/product/rating/insert",
     *     tags={"Product"},
     *     summary="Rating product",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rating product with optional images and videos",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="star", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="abcd"),
     *             @OA\Property(property="order_id", type="integer", example="1"),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(type="string", format="uri", example="http://example.com/image1.jpg")
     *             ),
     *             @OA\Property(
     *                 property="videos",
     *                 type="array",
     *                 @OA\Items(type="string", format="uri", example="http://example.com/video1.mp4")
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rating successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function insertRating(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id' => 'required|exists:products,id',
            'star' => 'required|in:1,2,3,4,5',
            'content' => 'required|max:3000',
            'order_id' => 'nullable|exists:orders,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        \DB::beginTransaction();
        try {
            // Check if the product is already rating by the user
            $isRating = \DB::table('products_rating')
                ->where('product_id', $request->id)
                ->where('user_id', auth('api')->id())
                ->exists();

            if ($isRating) return $this->sendResponse(null, __('api.product_rating_exits'));

            $lastId = \DB::table('products_rating')
                ->insertGetId([
                    'product_id' => $request->id,
                    'user_id' => auth('api')->id(),
                    'star' => $request->star,
                    'content' => $requestData['content'] ?? '',
                    'order_id' => $request->order_id ?? '',
                ]);

            if (!empty($request->images)) {
                foreach ($request->images as $itemI)
                    if ($request->hasFile($itemI))
                        \DB::table('products_rating_images')->insert([
                            'rating_id' => $lastId,
                            'image' => Product::uploadAndResize($itemI),
                            'type' => 1
                        ]);
            }

            if (!empty($request->videos)) {
                foreach ($request->videos as $itemV)
                    if ($request->hasFile($itemV))
                        \DB::table('products_rating_images')->insert([
                            'rating_id' => $lastId,
                            'image' => Product::uploadFile($itemV),
                            'type' => 2
                        ]);
            }

            \DB::commit();

            return $this->sendResponse(null, __('errors.PRODUCT_RATING_ADD'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/product/rating/reply",
     *     tags={"Product"},
     *     summary="Reply rating product",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Reply rating product",
     *         @OA\JsonContent(
     *             @OA\Property(property="rating_id", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="abcd"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rating successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function replyRating(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'rating_id' => 'required|exists:products_rating,id',
            'content' => 'required|max:3000',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $requestData['user_id'] = auth('api')->id();
            ProductRatingReply::create($requestData);
            return $this->sendResponse(null, __('PRODUCT_RATING_REPLY'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/product/upload",
     *     tags={"Product"},
     *     summary="Upload image",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Upload image file",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"image", "type"},
     *                 @OA\Property(property="image", type="string", format="binary", description="File image upload"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Upload successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function uploadImage(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'image' => 'required|image|max:10048', // Ensure that 'images' is an array
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {

            if ($request->hasFile('image'))
                $image = Product::uploadAndResize($request->image);
            else
                $image = null;

            return $this->sendResponse($image, __('UPLOAD_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

}

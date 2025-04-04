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
     *         name="sort_rate",
     *         in="query",
     *         description="sort_rate(asc,desc)",
     *         required=false,
     *         example="",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_distance",
     *         in="query",
     *         description="sort_distance(asc,desc)",
     *         required=false,
     *         example="",
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
            $productsQuery = Product::with('store')->whereHas('store', function ($query) {
                // Áp dụng điều kiện vào relation 'store'
                $query->where('active', 1); // Ví dụ điều kiện 'store' có trạng thái 'active'
            }); // Initialize the query

            // Apply keyword search
            if ($keywords != '') {
                $productsQuery->where('name', 'like', "%$keywords%");
            }

            // Apply store_id search
            if ($storeId != '') {
                $productsQuery->whereHas('categories', function ($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                });
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
            if ($sortRate) {
                $productsQuery->withAvg('rating', 'star') // Calculate the average star rating for each store
                ->orderBy('rating_avg_star', $sortRate); // Order by the average rating in ascending or descending order
            }

            // Sorting by distance (if applicable)
            if ($latitude && $longitude && $radius && $sortDistance) {
                $productsQuery->orderByRaw('distance ' . strtoupper($sortDistance)); // Order by distance, in ascending/descending order
            }

            // Pagination with limit and offset
            $products = $productsQuery->whereNull('products.deleted_at')->skip($offset)->take($limit)->get();

            return $this->sendResponse(ProductResource::collection($products), __('GET_PRODUCTS_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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

            return $this->sendResponse(new ProductResource($data), __("GET_DETAIL_PRODUCT"));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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

            return $this->sendResponse(ProductResource::collection($data), __('GET_PRODUCTS_FAVORITE'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *             @OA\Property(property="name", type="string", example="0964541340"),
     *             @OA\Property(property="price", type="double", example="123456"),
     *             @OA\Property(property="image", type="string", example="abcd"),
     *             @OA\Property(property="description", type="string", example="Mô tả"),
     *             @OA\Property(property="status", type="integer", example="1", description="1:Hiện, 0:Ẩn"),
     *             @OA\Property(property="store_id", type="integer", example="1"),
     *             @OA\Property(property="category_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Thể loại"),
     *             @OA\Property(property="variation_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Biến thể"),
     *             @OA\Property(property="group_topping_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="ID group topping"),
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
     *                  {"day": 1, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 2, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 3, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 4, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 5, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 6, "hours": {"10:00", "15:00"}, "is_off": 0},
     *                  {"day": 7, "hours": {"10:00", "15:00"}, "is_off": 0}
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
                'name' => 'required|max:120',
                'status' => 'nullable|in:0,1',
                'store_id' => 'required|exists:stores,id',
                'category_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $categoryId) {
                                if (!\DB::table('categories')->where('id', $categoryId)->exists()) {
                                    $fail("The category ID $categoryId does not exist.");
                                }
                            }
                        }
                    },
                ],
                'variation_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $variationId) {
                                if (!\DB::table('variations')->where('id', $variationId)->exists()) {
                                    $fail("The variation ID $variationId does not exist.");
                                }
                            }
                        }
                    },
                ],
                'group_topping_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $groupId) {
                                if (!\DB::table('toppings_group')->where('id', $groupId)->exists()) {
                                    $fail("The group ID $groupId does not exist.");
                                }
                            }
                        }
                    },
                ]
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

            if (is_array($request->operating_hours) && !empty($request->operating_hours)) {
                // Cập nhật giờ hoạt động
                $hoursData = $request->operating_hours;
                $data->updateStoreHours($hoursData);
            } else {
                unset($requestData['operating_hours']);
            }

            if (is_array($request->category_ids) && !empty($request->category_ids)) {
                // Adding multiple categories
                $storeId = $request->store_id;
                $categoryIds = $request->category_ids;
                $categoryData = [];
                foreach ($categoryIds as $categoryId) {
                    $categoryData[$categoryId] = ['store_id' => $storeId]; // Add user_id to the pivot data
                }
                // Sync categories with the user_id in the pivot table
                $data->categories()->syncWithoutDetaching($categoryData);
            } else
                unset($requestData['category_ids']);

            if (is_array($request->group_topping_ids) && !empty($request->group_topping_ids)) {
                // Adding multiple groups
                $groupIds = $request->group_topping_ids;
                $data->groups()->syncWithoutDetaching($groupIds);
            } else
                unset($requestData['group_topping_ids']);

            if (is_array($request->variation_ids) && !empty($request->variation_ids)) {
                // Adding multiple groups
                $variationIds = $request->variation_ids;
                $data->variations()->syncWithoutDetaching($variationIds);
            } else
                unset($requestData['variation_ids']);

            \DB::commit();
            return $this->sendResponse(new ProductResource($data), __('PRODUCT_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/product/update",
     *     tags={"Product"},
     *     summary="Update product",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product object that needs to be update",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="name", type="string", example="name product"),
     *             @OA\Property(property="price", type="double", example="50000"),
     *             @OA\Property(property="image", type="string", example="abcd"),
     *             @OA\Property(property="description", type="string", example="Mô tả"),
     *             @OA\Property(property="available_into", type="datetime", example="2025-03-28 10:20"),
     *             @OA\Property(property="status", type="integer", example="1", description="1:Hiện, 0:Ẩn"),
     *             @OA\Property(property="store_id", type="integer", example="1"),
     *             @OA\Property(property="category_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Thể loại"),
     *             @OA\Property(property="variation_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Biến thể"),
     *             @OA\Property(property="group_topping_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="ID group topping"),
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
     *                  {"day": 1, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 2, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 3, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 4, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 5, "hours": {"09:00", "18:00"}, "is_off": 0},
     *                  {"day": 6, "hours": {"10:00", "15:00"}, "is_off": 0},
     *                  {"day": 7, "hours": {"10:00", "15:00"}, "is_off": 0}
     *              },
     *              description="Operating hours for each day of the week"
     *             ),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update product Successful"),
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
                'name' => 'nullable|max:120',
                'description' => 'nullable|max:3000',
                'active' => 'nullable|in:0,1',
                'store_id' => 'nullable|exists:stores,id',
                'time_open' => 'nullable|date_format:Y-m-d H:i',
                'time_close' => 'nullable|date_format:Y-m-d H:i|after:time_open',
                'category_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $categoryId) {
                                if (!\DB::table('categories')->where('id', $categoryId)->exists()) {
                                    $fail("The category ID $categoryId does not exist.");
                                }
                            }
                        }
                    },
                ],
                'variation_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $variationId) {
                                if (!\DB::table('variations')->where('id', $variationId)->exists()) {
                                    $fail("The variation ID $variationId does not exist.");
                                }
                            }
                        }
                    },
                ],
                'group_topping_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $groupId) {
                                if (!\DB::table('toppings_group')->where('id', $groupId)->exists()) {
                                    $fail("The group ID $groupId does not exist.");
                                }
                            }
                        }
                    },
                ]
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $id = $request->id;

            if ($request->time_open != null && $request->time_open != null) $requestData['status'] = 0;

            $data = Product::find($id);

            $data->update($requestData);

            $data->refresh();

            if (is_array($request->operating_hours) && !empty($request->operating_hours)) {
                // Cập nhật giờ hoạt động
                $hoursData = $request->operating_hours;
                $data->updateStoreHours($hoursData);
            } else {
                unset($requestData['operating_hours']);
            }

            if (is_array($request->category_ids) && !empty($request->category_ids)) {

                // Adding multiple categories
                $storeId = $data->store_id;  // Assuming $data is your Product model
                $categoryIds = $request->category_ids;
                $categoryData = [];

                //Xoá hết thể loại
                \DB::table('categories_products')->where([['store_id', $storeId], ['product_id', $id]])->delete();
                // Prepare the pivot data (store_id) for each category
                foreach ($categoryIds as $categoryId) {
                    $categoryData[$categoryId] = ['store_id' => $storeId];  // Adding store_id to the pivot data
                }

                // Sync categories with the store_id in the pivot table (categories_products)
                $data->categories()->attach($categoryData);
            } else
                unset($requestData['category_ids']);

            if (is_array($request->group_topping_ids) && !empty($request->group_topping_ids)) {
                // Adding multiple groups
                $groupIds = $request->group_topping_ids;
                $data->groups()->sync($groupIds);
            } else
                unset($requestData['group_topping_ids']);

            if (is_array($request->variation_ids) && !empty($request->variation_ids)) {
                // Adding multiple groups
                $variationIds = $request->variation_ids;
                $data->variations()->sync($variationIds);
            } else
                unset($requestData['variation_ids']);


            \DB::commit();
            return $this->sendResponse(new ProductResource($data), __('errors.PRODUCT_UPDATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
        \DB::beginTransaction();
        try {
            $id = $request->id;

            \DB::table('products')->where('id', $id)->update([
                'deleted_at' => now()
            ]);
            //Delete product in group
            \DB::table('products_groups')->where('product_id', $id)->delete();

            //Delete product in category
            \DB::table('categories_products')->where('product_id', $id)->delete();

            \DB::commit();
            return $this->sendResponse(null, __('PRODUCT_DELETED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *                 required={"image"},
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
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

}

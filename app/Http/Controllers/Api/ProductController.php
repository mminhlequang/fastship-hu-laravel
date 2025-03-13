<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductDetailResource;
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
     *     path="/api/v1/product",
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
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';

        try {
            $data = Product::with('store')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            });

            $data = $data->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(ProductResource::collection($data), 'Get all products successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product/by_lat_lng",
     *     tags={"Product"},
     *     summary="Get all product by lat lng",
     *     @OA\Parameter(
     *         name="keywords",
     *         in="query",
     *         description="keywords",
     *         required=false,
     *         @OA\Schema(type="string")
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
     * )
     */
    public function getListByLatLng(Request $request)
    {
        $latitude = $request->lat ?? "16.481734013476487";
        $longitude = $request->lng ?? "107.60490258435505";
        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';

        try {
            $data = Product::with('store')
                ->when($keywords != '', function ($query) use ($keywords) {
                    $query->where('name', 'like', "%$keywords%");
                })
                ->whereHas('store', function ($query) use ($latitude, $longitude) {
                    $query->selectRaw("*, 
                (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(lat)) * COS(RADIANS(lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(lat)))) AS distance",
                        [$latitude, $longitude, $latitude]);
                })
                ->orderBy("distance")
                ->whereNull('deleted_at')
                ->skip($offset)
                ->take($limit)
                ->get();

            return $this->sendResponse(ProductResource::collection($data), 'Get all products successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/product/by_store",
     *     tags={"Product"},
     *     summary="Get all product by store",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="store_id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
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
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getListByStore(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';

        $customer = Customer::getAuthorizationUser($request);

        $storeId = $request->store_id;

        try {
            $data = Product::with('store')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            });

            $data = $data->where('store_id', $storeId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(ProductResource::collection($data), 'Get all products successfully.');
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

            return $this->sendResponse(new ProductDetailResource($data), "Get detail successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product/favorite",
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

        $customer = Customer::getAuthorizationUser($request);


        try {
            $ids = \DB::table('products_favorite')->where('user_id', $customer->id)->pluck('product_id')->toArray();

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
     *     path="/api/v1/product/rating",
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

        $customer = Customer::getAuthorizationUser($request);


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
     *         description="product object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="name_vi", type="string", example="0964541340"),
     *             @OA\Property(property="name_en", type="string", example="0964541340"),
     *             @OA\Property(property="name_zh", type="string", example="0964541340"),
     *             @OA\Property(property="name_hu", type="string", example="0964541340"),
     *             @OA\Property(property="price", type="double", example="123456"),
     *             @OA\Property(property="image", type="string", example="abcd"),
     *             @OA\Property(property="description", type="string", example="abcd"),
     *             @OA\Property(property="content", type="string", example="abcd"),
     *             @OA\Property(property="category_id", type="integer", example="1"),
     *             @OA\Property(property="active", type="integer", example="1", description="1:Hiện, 0:Ẩn"),
     *             @OA\Property(property="group_id", type="integer", example="1", description="ID group topping"),
     *             @OA\Property(property="store_id", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create club Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function create(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);

        $validator = Validator::make(
            $request->all(),
            [
                'name_vi' => 'required|min:5|max:120',
                'name_en' => 'required|min:5|max:120',
                'name_zh' => 'required|min:5|max:120',
                'name_hu' => 'required|min:5|max:120',
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'description' => 'nullable|max:120',
                'content' => 'nullable|max:3000',
                'active' => 'nullable|in:0,1',
                'group_id' => 'nullable|exists:toppings_group:id',
                'store_id' => 'required|exists:stores,id',
            ],
            [
                'name_vi.required' => 'Tên sản phẩm bắt buộc phải có',
                'name_vi.min' => 'Tên sản phẩm tối thiểu 5 kí tự',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = product::uploadAndResize($request->file('image'));

            $requestData['creator_id'] = $customer->id;

            $data = Product::create($requestData);

            return $this->sendResponse(new ProductResource($data), __('errors.PRODUCT_CREATED'));
        } catch (\Exception $e) {
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
     *             @OA\Property(property="description", type="string", example="abcd"),
     *             @OA\Property(property="content", type="string", example="abcd"),
     *             @OA\Property(property="category_id", type="integer", example="1"),
     *             @OA\Property(property="active", type="integer", example="1", description="1:Hiện, 0:Ẩn"),
     *             @OA\Property(property="group_id", type="integer", example="1", description="ID group topping"),
     *             @OA\Property(property="store_id", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update club Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:products,id',
                'name_vi' => 'required|min:5|max:120',
                'name_en' => 'required|min:5|max:120',
                'name_zh' => 'required|min:5|max:120',
                'name_hu' => 'required|min:5|max:120',
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'description' => 'nullable|max:120',
                'content' => 'nullable|max:3000',
                'active' => 'nullable|in:0,1',
                'group_id' => 'nullable|exists:toppings_group:id',
                'store_id' => 'required|exists:stores,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = product::uploadAndResize($request->file('image'));

            $data = Product::find($requestData['id']);

            $data->update($requestData);

            $data->refresh();

            return $this->sendResponse(new ProductResource($data), __('errors.PRODUCT_UPDATED'));
        } catch (\Exception $e) {
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
        $customer = Customer::getAuthorizationUser($request);

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
        $customer = Customer::getAuthorizationUser($request);

        try {
            // Check if the product is already favorited by the user
            $isFavorite = \DB::table('products_favorite')
                ->where('product_id', $request->id)
                ->where('user_id', $customer->id)
                ->exists();

            // If not favorited, insert into the database
            if (!$isFavorite) {
                \DB::table('products_favorite')->insert([
                    'product_id' => $request->id,
                    'user_id' => $customer->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(null, __('errors.PRODUCT_FAVORITE_ADD'));
            } else {
                \DB::table('products_favorite')
                    ->where('product_id', $request->id)
                    ->where('user_id', $customer->id)
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
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);

        \DB::beginTransaction();
        try {
            // Check if the product is already rating by the user
            $isRating = \DB::table('products_rating')
                ->where('product_id', $request->id)
                ->where('user_id', $customer->id)
                ->exists();

            if ($isRating) return $this->sendResponse(null, __('api.product_rating_exits'));

            $lastId = \DB::table('products_rating')
                ->insertGetId([
                    'product_id' => $request->id,
                    'user_id' => $customer->id,
                    'star' => $request->star,
                    'content' => $requestData['content'] ?? '',
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
        $customer = Customer::getAuthorizationUser($request);

        try {
            $requestData['user_id'] = $customer->id;
            ProductRatingReply::create($requestData);
            return $this->sendResponse(null, __('errors.PRODUCT_RATING_REPLY'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

}

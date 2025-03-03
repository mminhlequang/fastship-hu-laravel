<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StoreRatingResource;
use App\Http\Resources\StoreResource;
use App\Models\AddressDelivery;
use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreRating;
use App\Models\StoreRatingReply;
use Illuminate\Http\Request;
use Validator;


class StoreController extends BaseController
{


    /**
     * @OA\Get(
     *     path="/api/v1/store",
     *     tags={"Store"},
     *     summary="Get all store",
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
     *     @OA\Response(response="200", description="Get all stores"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';

        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");

        try {
            $data = Store::with('creator')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name', 'like', "%$keywords%");
            });

            $data = $data->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(StoreResource::collection($data), 'Get all stores successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/store/by_lat_lng",
     *     tags={"Store"},
     *     summary="Get all store by lat lng",
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
     *     @OA\Response(response="200", description="Get all stores")
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
            $data = Store::with('creator')
                ->when($keywords != '', function ($query) use ($keywords) {
                    $query->where('name', 'like', "%$keywords%");
                })
                ->selectRaw("*, 
        (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(lat)) * COS(RADIANS(lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(lat)))) AS distance",
                    [$latitude, $longitude, $latitude])
                ->orderBy("distance")
                ->whereNull('deleted_at')
                ->skip($offset)
                ->take($limit)
                ->get();

            return $this->sendResponse(StoreResource::collection($data), 'Get all stores successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/store/by_user",
     *     tags={"Store"},
     *     summary="Get all store by user",
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
     *     @OA\Response(response="200", description="Get all stores"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getListByUser(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';

        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        $customerId = $customer->id;

        try {
            $data = Store::with('creator')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name', 'like', "%$keywords%");
            });

            $data = $data->where('creator_id', $customerId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(StoreResource::collection($data), 'Get all stores successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/store/detail",
     *     tags={"Store"},
     *     summary="Get detail store by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID of the store",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Store details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Store not found"
     *     )
     * )
     */
    public function detail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id' => 'required|exists:stores,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $data = Store::find($requestData['id']);

            return $this->sendResponse(new StoreResource($data), "Get detail successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/store/rating",
     *     tags={"Store"},
     *     summary="Get all rating store",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="ID store",
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
            'store_id' => 'required|exists:stores,id',
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
        if (!$customer)
            return $this->sendError("Invalid signature");

        try {

            $data = StoreRating::with('user')->when($star != '', function ($query) use ($star) {
                $query->where('star', $star);
            })->when($date != 3, function ($query) use ($date) {
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
                ->where('store_id', $request->store_id)
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
                'data' => StoreRatingResource::collection($data)
            ], 'Get all rating successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/store/create",
     *     tags={"Store"},
     *     summary="Create store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Store object that needs to be created",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="0964541340"),
     *             @OA\Property(property="phone", type="string", example="123456"),
     *             @OA\Property(property="address", type="string", example="abcd"),
     *             @OA\Property(property="lat", type="double", example="123.102"),
     *             @OA\Property(property="lng", type="double", example="12.054"),
     *             @OA\Property(property="street", type="string", example="abcd"),
     *             @OA\Property(property="zip", type="string", example="abcd"),
     *             @OA\Property(property="city", type="string", example="abcd"),
     *             @OA\Property(property="state", type="string", example="abcd"),
     *             @OA\Property(property="country", type="string", example="abcd"),
     *             @OA\Property(property="country_code", type="string", example="abcd"),
     *             @OA\Property(property="image", type="string", format="binary"),
     *             @OA\Property(property="banner", type="string", format="binary"),
     *             @OA\Property(property="operating_hours", type="string", description="Thời gian hoạt động kiểu array")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create store Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function create(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:5|max:120',
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'banner' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'phone' => 'required|digits:10',
                'address' => 'required|min:5|max:120',
                'lat' => 'nullable',
                'lng' => 'nullable',
                'street' => 'nullable|max:120',
                'zip' => 'nullable|max:120',
                'city' => 'nullable|max:120',
                'state' => 'nullable|max:120',
                'country' => 'nullable|max:120',
                'country_code' => 'nullable|max:120',
                'operating_hours' => 'nullable|array',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Store::uploadAndResize($request->file('image'));

            if ($request->hasFile('banner'))
                $requestData['banner'] = Store::uploadAndResize($request->file('banner'));

            $requestData['customer_id'] = $customer->id;

            $data = Store::create($requestData);

            return $this->sendResponse(new AddressDelivery($data), __('api.store_created'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/store/update",
     *     tags={"Store"},
     *     summary="Update store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="store object that needs to be update",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="name", type="string", example="0964541340"),
     *             @OA\Property(property="phone", type="string", example="123456"),
     *             @OA\Property(property="address", type="string", example="abcd"),
     *             @OA\Property(property="lat", type="double", example="123.102"),
     *             @OA\Property(property="lng", type="double", example="12.054"),
     *             @OA\Property(property="street", type="string", example="abcd"),
     *             @OA\Property(property="zip", type="string", example="abcd"),
     *             @OA\Property(property="city", type="string", example="abcd"),
     *             @OA\Property(property="state", type="string", example="abcd"),
     *             @OA\Property(property="country", type="string", example="abcd"),
     *             @OA\Property(property="country_code", type="string", example="abcd"),
     *             @OA\Property(property="image", type="string", format="binary"),
     *             @OA\Property(property="banner", type="string", format="binary"),
     *             @OA\Property(property="operating_hours", type="string", description="Thời gian hoạt động kiểu array"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update store Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {
        $requestData = $request->all();
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:stores,id',
                'name' => 'required|min:5|max:120',
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'banner' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'phone' => 'required|digits:10',
                'address' => 'required|min:5|max:120',
                'lat' => 'nullable',
                'lng' => 'nullable',
                'street' => 'nullable|max:120',
                'zip' => 'nullable|max:120',
                'city' => 'nullable|max:120',
                'state' => 'nullable|max:120',
                'country' => 'nullable|max:120',
                'country_code' => 'nullable|max:120',
                'operating_hours' => 'nullable|array',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Store::uploadAndResize($request->file('image'));

            if ($request->hasFile('banner'))
                $requestData['banner'] = Store::uploadAndResize($request->file('banner'));

            $data = Store::find($requestData['id']);

            $data->update($requestData);

            $data->refresh();

            return $this->sendResponse(new StoreResource($data), __('api_store_updated'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/store/delete",
     *     tags={"Store"},
     *     summary="Delete store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete store",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete successfully"
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
            'id' => 'required|exists:stores,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        try {
            \DB::table('stores')->where('id', $request->id)->update([
                'deleted_at' => now()
            ]);
            return $this->sendResponse(null, __('api.store_deleted'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/store/rating/insert",
     *     tags={"Store"},
     *     summary="Rating store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rating store with optional images and videos",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1, description="ID store"),
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
            'id' => 'required|exists:stores,id',
            'star' => 'required|in:1,2,3,4,5',
            'content' => 'required|max:3000',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        \DB::beginTransaction();
        try {
            // Check if the product is already rating by the user
            $isRatingS = \DB::table('stores_rating')
                ->where('store_id', $request->id)
                ->where('user_id', $customer->id)
                ->exists();

            if ($isRatingS) return $this->sendResponse(null, __('api.store_rating_exits'));

            $lastId = \DB::table('stores_rating')
                ->insertGetId([
                    'store_id' => $request->id,
                    'user_id' => $customer->id,
                    'star' => $request->star,
                    'content' => $requestData['content'] ?? '',
                ]);

            if (!empty($request->images)) {
                foreach ($request->images as $itemI)
                    if ($request->hasFile($itemI))
                        \DB::table('stores_rating_images')->insert([
                            'rating_id' => $lastId,
                            'image' => Store::uploadAndResize($itemI),
                            'type' => 1
                        ]);
            }

            if (!empty($request->videos)) {
                foreach ($request->videos as $itemV)
                    if ($request->hasFile($itemV))
                        \DB::table('stores_rating_images')->insert([
                            'rating_id' => $lastId,
                            'image' => Store::uploadFile($itemV),
                            'type' => 2
                        ]);
            }

            \DB::commit();

            return $this->sendResponse(null, __('api.store_rating'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/store/rating/reply",
     *     tags={"Store"},
     *     summary="Reply rating store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Reply rating store",
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
            'rating_id' => 'required|exists:stores_rating,id',
            'content' => 'required|max:3000',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        try {
            $requestData['user_id'] = $customer->id;
            StoreRatingReply::create($requestData);
            return $this->sendResponse(null, __('api.store_reply'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }

}

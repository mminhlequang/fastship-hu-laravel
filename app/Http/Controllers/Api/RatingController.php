<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CustomerRatingResource;
use App\Http\Resources\ProductRatingResource;
use App\Http\Resources\StoreRatingResource;
use App\Models\CustomerRating;
use App\Models\ProductRating;
use App\Models\ProductRatingReply;
use App\Models\Store;
use App\Models\StoreRating;
use App\Models\StoreRatingReply;
use Illuminate\Http\Request;
use Validator;

class RatingController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/rating/get_rating_product",
     *     tags={"Rating"},
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
     * )
     */
    public function getRatingProduct(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
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
            ], __('GET_RATING_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/rating/insert_rating_product",
     *     tags={"Rating"},
     *     summary="Rating product",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rating product with optional images and videos",
     *         @OA\JsonContent(
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="star", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="abcd"),
     *             @OA\Property(property="order_id", type="integer", example="1"),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(type="string", example="storage/products/image1.webp"),
     *                 description="Các ảnh khác"
     *             ),
     *             @OA\Property(
     *                 property="videos",
     *                 type="array",
     *                 @OA\Items(type="string", example="storage/products/image1.webp"),
     *                 description="Các video khác"
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

    public function insertRatingProduct(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'product_id' => 'required|exists:products,id',
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
                ->where('product_id', $request->product_id)
                ->where('user_id', auth('api')->id())
                ->exists();

            if ($isRating) return $this->sendResponse(null, __('PRODUCT_RATING_EXISTS'));

            $lastId = \DB::table('products_rating')
                ->insertGetId([
                    'product_id' => $request->product_id,
                    'user_id' => auth('api')->id(),
                    'star' => $request->star,
                    'content' => $requestData['content'] ?? '',
                    'order_id' => $request->order_id ?? '',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

            if (is_array($request->images) &&!empty($request->images)) {
                foreach ($request->images as $itemI) {
                    \DB::table('products_rating_images')->insert([
                        'rating_id' => $lastId,
                        'image' => $itemI,
                        'type' => 1
                    ]);
                }
            } else {
                unset($requestData['images']);
            }

            if (is_array($request->videos) && !empty($request->videos)) {
                foreach ($request->videos as $itemV) {
                    \DB::table('products_rating_images')->insert([
                        'rating_id' => $lastId,
                        'image' => $itemV,
                        'type' => 2
                    ]);
                }
            } else {
                unset($requestData['videos']);
            }

            \DB::commit();

            return $this->sendResponse(null, __('PRODUCT_RATING_ADD'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/rating/reply_rating_product",
     *     tags={"Rating"},
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

    public function replyRatingProduct(Request $request)
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
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Get(
     *     path="/api/v1/rating/get_rating_store",
     *     tags={"Rating"},
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
     * )
     */
    public function getRatingStore(Request $request)
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
            ], __('GET_RATING_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/rating/insert_rating_store",
     *     tags={"Rating"},
     *     summary="Rating store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rating store with optional images and videos",
     *         @OA\JsonContent(
     *             @OA\Property(property="store_id", type="integer", example=1, description="ID store"),
     *             @OA\Property(property="star", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="abcd"),
     *             @OA\Property(property="order_id", type="integer", example="1"),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(type="string", example="storage/products/image1.webp"),
     *                 description="Các ảnh khác"
     *             ),
     *             @OA\Property(
     *                 property="videos",
     *                 type="array",
     *                 @OA\Items(type="string", example="storage/products/image1.webp"),
     *                 description="Các ảnh khác"
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

    public function insertRatingStore(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'store_id' => 'required|exists:stores,id',
            'star' => 'required|in:1,2,3,4,5',
            'content' => 'required|max:3000',
            'order_id' => 'nullable|exists:orders,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        \DB::beginTransaction();
        try {
            // Check if the product is already rating by the user
            $isRatingS = \DB::table('stores_rating')
                ->where('store_id', $request->store_id)
                ->where('user_id', auth('api')->id())
                ->exists();

            if ($isRatingS) return $this->sendResponse(null, __('api.store_rating_exits'));

            $lastId = \DB::table('stores_rating')
                ->insertGetId([
                    'store_id' => $request->store_id,
                    'user_id' => auth('api')->id(),
                    'star' => $request->star,
                    'content' => $requestData['content'] ?? '',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

            if (is_array($request->images) && !empty($request->images)) {
                foreach ($request->images as $itemI) {
                    \DB::table('stores_rating_images')->insert([
                        'rating_id' => $lastId,
                        'image' => $itemI,
                        'type' => 1
                    ]);
                }
            } else {
                unset($requestData['images']);
            }

            if (is_array($request->videos) && !empty($request->videos)) {
                foreach ($request->videos as $itemV) {
                    \DB::table('stores_rating_images')->insert([
                        'rating_id' => $lastId,
                        'image' => $itemV,
                        'type' => 2
                    ]);
                }
            } else {
                unset($requestData['videos']);
            }

            \DB::commit();

            return $this->sendResponse(null, __('STORE_RATING_ADD'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/rating/reply_rating_store",
     *     tags={"Rating"},
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

    public function replyRatingStore(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'rating_id' => 'required|exists:stores_rating,id',
            'content' => 'required|max:3000',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $requestData['user_id'] = auth('api')->id();
            StoreRatingReply::create($requestData);
            return $this->sendResponse(null, __('STORE_RATING_REPLY'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Get(
     *     path="/api/v1/rating/get_rating_driver",
     *     tags={"Rating"},
     *     summary="Get all rating driver",
     *     @OA\Parameter(
     *         name="driver_id",
     *         in="query",
     *         description="driver_id",
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
     *         name="from",
     *         in="query",
     *         description="From date",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="To date ko truyền mặc định lấy now() ",
     *         required=false,
     *         @OA\Schema(type="date")
     *     ),
     *     @OA\Parameter(
     *         name="star",
     *         in="query",
     *         description="star",
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
     * )
     */
    public function getRatingDriver(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'driver_id' => 'required|exists:customers,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';
        $star = $request->star ?? '';
        $from = $request->from ?? '';
        $to = $request->to ?? now();

        try {

            $data = CustomerRating::with('user')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('content', 'like', "%$keywords%");
            })->when($star != '', function ($query) use ($star) {
                $query->where('star', $star);
            })->when($from != '', function ($query) use ($from, $to) {
                $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            });

            $data = $data->where('user_id', $request->driver_id)->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(CustomerRatingResource::collection($data), __('GET_RATING_DRIVER'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/rating/insert_rating_driver",
     *     tags={"Rating"},
     *     summary="Rating driver",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rating product with optional images and videos",
     *         @OA\JsonContent(
     *             @OA\Property(property="driver_id", type="integer", example=1),
     *             @OA\Property(property="star", type="integer", example=1),
     *             @OA\Property(property="text", type="string", example="abcd"),
     *             @OA\Property(property="order_id", type="integer", example="1"),
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

    public function insertRatingDriver(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'driver_id' => 'required|exists:customers,id',
            'star' => 'required|in:1,2,3,4,5',
            'text' => 'required|max:3000',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            // Check if the driver is already rating by the user
            $isRating = \DB::table('customers_rating')
                ->where('user_id', $request->driver_id)
                ->where('creator_id', auth('api')->id())
                ->exists();

            if ($isRating) return $this->sendResponse(null, __('DRIVER_RATING_EXISTS'));

            \DB::table('customers_rating')
                ->insert([
                    'user_id' => $request->driver_id,
                    'creator_id' => auth('api')->id(),
                    'star' => $request->star,
                    'content' => $request->text ?? '',
                    'order_id' => $request->order_id
                ]);

            return $this->sendResponse(null, __('DRIVER_RATING_ADD'));

        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/rating/upload",
     *     tags={"Rating"},
     *     summary="Upload file",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Upload a file",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="The file to be uploaded"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File uploaded successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function uploadFile(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'file' => 'required|max:10048', // Ensure that 'images' is an array
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {

            if ($request->hasFile('file'))
                $file = Store::uploadFile($request->file, 'ratings');
            else
                $file = null;

            return $this->sendResponse($file, __('UPLOAD_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

}

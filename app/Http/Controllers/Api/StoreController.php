<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DataBaseResource;
use App\Http\Resources\StoreMenuResource;
use App\Http\Resources\StoreRatingResource;
use App\Http\Resources\StoreResource;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreRating;
use App\Models\StoreRatingReply;
use App\Models\ToppingGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;


class StoreController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/store/get_stores",
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
     *     @OA\Response(response="200", description="Get all stores"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getStores(Request $request)
    {
        $now = Carbon::now();
        $dayOfWeek = $now->dayOfWeek;  // Lấy ngày trong tuần
        $currentTime = $now->format('H:i');  // Lấy giờ hiện tại

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';
        $latitude = $request->lat ?? '';
        $longitude = $request->lng ?? '';
        $radius = $request->radius ?? '';
        $rate = $request->rate ?? '';
        $isFavorite = $request->is_favorite ?? null;
        $isPopular = $request->is_popular ?? null;
        $isTopSeller = $request->is_topseller ?? null;
        $sortRate = $request->sort_rate ?? 'desc'; // Default to 'desc'
        $sortDistance = $request->sort_distance ?? 'asc'; // Default to 'asc'

        try {

            $storesQuery = Store::with('creator')->whereNull('deleted_at');

            // Apply keyword search
            if ($keywords != '') {
                $storesQuery->where('name', 'like', "%$keywords%");
            }

            // Apply rate filter based on the average rating from the ratings relationship
            if ($rate != '') {
                $storesQuery->whereHas('rating', function ($query) use ($rate) {
                    $query->havingRaw('AVG(star) >= ?', [$rate]);
                });
            }

            // Apply popular filter (based on the number of orders)
            if ($isTopSeller !== null) {
                $storesQuery->withCount('orders') // Counting the number of orders for each store
                ->orderBy('orders_count', 'desc'); // Sorting based on order count in descending order
            }

            // Apply featured filter (based on the number of favorites)
            if ($isPopular !== null) {
                $storesQuery->withCount('favorites') // Counting the number of favorites for each store
                ->orderBy('favorites_count', 'desc'); // Sorting based on favorite count in descending order
            }

            // Apply favorite filter (only if the user is logged in)
            if ($isFavorite && auth('api')->id() != null) {
                $userId = auth('api')->id();
                $storesQuery->whereHas('favorites', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                });
            }

            // Apply distance filter (if latitude, longitude, and radius are provided)
            if ($latitude && $longitude && $radius) {
                $storesQuery->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
                    ->having('distance', '<=', $radius);
            }

            // Apply sorting by average rate based on the ratings relationship
            if ($rate != '' && $sortRate) {
                $storesQuery->withAvg('rating', 'star') // Calculate the average star rating for each store
                ->orderBy('rating_avg_star', $sortRate); // Order by the average rating in ascending or descending order
            }

            if ($latitude && $longitude && $radius && $sortDistance) {
                $storesQuery->orderBy('distance', $sortDistance);
            }

            // Pagination with limit and offset
            $stores = $storesQuery->whereHas('hours', function ($query) use ($dayOfWeek, $currentTime) {
                $query->where('day', $dayOfWeek)
                    ->whereTime('start_time', '<=', $currentTime)
                    ->whereTime('end_time', '>=', $currentTime);
            })->skip($offset)->take($limit)->get();

            return $this->sendResponse(StoreResource::collection($stores), __('GET_STORES_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
     *     @OA\Response(response="200", description="Get all stores"),
     *     security={{"bearerAuth":{}}},
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

            return $this->sendResponse(StoreResource::collection($data), __('GET_STORES_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/store/get_my_stores",
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
     *         name="active",
     *         in="query",
     *         description="Active",
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
     *     @OA\Response(response="200", description="Get all stores"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getListByUser(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';
        $active = $request->active ?? 1;

        $customer = Customer::getAuthorizationUser($request);

        $customerId = $customer->id;

        try {
            $data = Store::with('creator')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name', 'like', "%$keywords%");
            })->when($active, function ($query) use ($active) {
                $query->where('active', $active);
            });

            $data = $data->where('creator_id', $customerId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(StoreResource::collection($data), __('GET_STORES_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
     *     ),
     *     security={{"bearerAuth":{}}},
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

            return $this->sendResponse(new StoreResource($data), __("GET_DETAIL_SUCCESS"));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/store/get_rating",
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
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/store/get_menus",
     *     tags={"Store"},
     *     summary="Get menus store (menu, topping)",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Id của store",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type(1:Menu, 2:Topping)",
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
     *     @OA\Response(response="200", description="Get all stores"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getMenus(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $storeId = $request->store_id;
        $type = $request->type ?? 1;

        try {
            //1:Menu, 2:Topping
            if ($type == 1) {
                $ids = \DB::table('categories_stores')->where('store_id', $storeId)->pluck('category_id')->toArray();
                $data = Category::with('products')->whereIn('category_id', $ids)->whereNull('parent_id')->has('products');
            } else {
                $data = ToppingGroup::with('toppings')->where('store_id', $storeId)->has('toppings');
            }
            $data = $data->orderBy(\DB::raw("SUBSTRING_INDEX(name_vi, ' ', -1)"), 'asc')->skip($offset)->take($limit)->get();

            return $this->sendResponse(StoreMenuResource::collection($data), __('GET_LIST_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
     *             @OA\Property(property="name", type="string", example="Store A"),
     *             @OA\Property(property="type", type="string", description="individual house, business"),
     *             @OA\Property(property="phone", type="string", example="123456", description="SĐT"),
     *             @OA\Property(property="phone_other", type="string", example="123456", description="SĐT khác"),
     *             @OA\Property(property="phone_contact", type="string", example="123456", description="SĐT liên hệ"),
     *             @OA\Property(property="email", type="string", example="email@gmail.com"),
     *             @OA\Property(property="license", type="string", example="123213", description="Mã số thuế"),
     *             @OA\Property(property="cccd", type="string", example="123456789"),
     *             @OA\Property(property="cccd_date", type="string", example="2000-05-15"),
     *             @OA\Property(property="image", type="string"),
     *             @OA\Property(property="banner", type="string"),
     *             @OA\Property(property="image_cccd_before", type="string"),
     *             @OA\Property(property="image_cccd_after", type="string"),
     *             @OA\Property(property="image_license", type="string", description="Ảnh giấy phép kinh doanh"),
     *             @OA\Property(property="image_tax_code", type="string", description="Ảnh giấy mã số thuế"),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(type="string", example="storage/products/image1.webp"),
     *                 description="Các ảnh khác"
     *             ),
     *             @OA\Property(property="tax_code", type="string", description="Mã số thuế"),
     *             @OA\Property(property="service_id", type="integer", example="1", description="Loại dịch vụ"),
     *             @OA\Property(property="services", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Dịch vụ"),
     *             @OA\Property(property="foods", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Ẩm thực"),
     *             @OA\Property(property="products", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Sản phẩm đặc trưng"),
     *             @OA\Property(property="fee", type="double", example="0", description="Phí gửi xe"),
     *             @OA\Property(
     *              property="operating_hours",
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="day", type="integer", example=1, description="Day of the week"),
     *                   @OA\Property(property="hours", type="array", @OA\Items(type="string"), example={"09:00", "18:00"}, description="Operating hours for the day")
     *              ),
     *              description="Operating hours for each day of the week"
     *              ),
     *             @OA\Property(property="address", type="string", example="abcd"),
     *             @OA\Property(property="lat", type="double", example="123.102"),
     *             @OA\Property(property="lng", type="double", example="12.054"),
     *             @OA\Property(property="street", type="string", example="abcd"),
     *             @OA\Property(property="zip", type="string", example="abcd"),
     *             @OA\Property(property="city", type="string", example="abcd"),
     *             @OA\Property(property="state", type="string", example="abcd"),
     *             @OA\Property(property="country", type="string", example="abcd"),
     *             @OA\Property(property="country_code", type="string", example="abcd"),
     *             @OA\Property(property="card_bank", type="string", example="012345678910", description="Tên ngân hàng"),
     *             @OA\Property(property="card_number", type="string", example="012345678910", description="Số tài khoản "),
     *             @OA\Property(property="card_holder_name", type="string", example="012345678910", description="Chủ thẻ"),
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

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:5|max:120',
                'phone' => 'required',
                'phone_other' => 'nullable',
                'phone_contact' => 'nullable',
                'cccd' => 'required',
                'cccd_date' => 'nullable|date_format:Y-m-d',
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
        \DB::beginTransaction();
        try {

            if (!empty($request->services))
                $requestData['services'] = DataBaseResource::collection(Service::whereIn('id', $request->services)->select(['id', 'name_vi'])->get());

            if (!empty($request->foods))
                $requestData['foods'] = DataBaseResource::collection(Service::whereIn('id', $request->foods)->select(['id', 'name_vi'])->get());

            if (!empty($request->products))
                $requestData['products'] = DataBaseResource::collection(Service::whereIn('id', $request->products)->select(['id', 'name_vi'])->get());

            $requestData['creator_id'] = $customer->id;

            $data = Store::create($requestData);

            if (!empty($request->operating_hours)) {
                // Cập nhật giờ hoạt động
                $hoursData = $request->operating_hours;
                $data->updateStoreHours($hoursData);
            }

            if (!empty($request->images)) {
                $images = $request->images;
                foreach ($images as $itemI)
                    \DB::table('stores_images')->insert([
                        'store_id' => $data->id,
                        'image' => $itemI
                    ]);
            }
            \DB::commit();
            return $this->sendResponse(null, __('errors.STORE_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
     *             @OA\Property(property="name", type="string", example="Store A"),
     *             @OA\Property(property="type", type="string", example="individual" ),
     *             @OA\Property(property="phone", type="string", example="123456", description="SĐT"),
     *             @OA\Property(property="phone_other", type="string", example="123456", description="SĐT khác"),
     *             @OA\Property(property="phone_contact", type="string", example="123456", description="SĐT liên hệ"),
     *             @OA\Property(property="email", type="string", example="email@gmail.com"),
     *             @OA\Property(property="license", type="string", example="123213", description="Mã số thuế"),
     *             @OA\Property(property="cccd", type="string", example="123456789"),
     *             @OA\Property(property="cccd_date", type="string", example="2000-05-15"),
     *             @OA\Property(property="image", type="string"),
     *             @OA\Property(property="banner", type="string"),
     *             @OA\Property(property="image_cccd_before", type="string"),
     *             @OA\Property(property="image_cccd_after", type="string"),
     *             @OA\Property(property="image_license", type="string", description="Ảnh giấy phép kinh doanh"),
     *             @OA\Property(property="image_tax_code", type="string", description="Ảnh mã số thuế"),
     *             @OA\Property(property="tax_code", type="string", description="Mã số thuế"),
     *             @OA\Property(property="service_id", type="integer", example="1", description="Loại dịch vụ"),
     *             @OA\Property(property="services", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Dịch vụ"),
     *             @OA\Property(property="foods", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Ẩm thực"),
     *             @OA\Property(property="products", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Sản phẩm đặc trưng"),
     *             @OA\Property(property="fee", type="double", example="0", description="Phí gửi xe"),
     *             @OA\Property(
     *              property="operating_hours",
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="day", type="integer", example=1, description="Day of the week (1 = Monday, 2 = Tuesday, ..., 7 = Sunday)"),
     *                   @OA\Property(property="hours", type="array", @OA\Items(type="string"), example={"09:00", "18:00"}, description="Operating hours for the day")
     *              ),
     *              description="Operating hours for each day of the week"
     *              ),
     *             @OA\Property(property="address", type="string", example="abcd"),
     *             @OA\Property(property="lat", type="double", example="123.102"),
     *             @OA\Property(property="lng", type="double", example="12.054"),
     *             @OA\Property(property="street", type="string", example="abcd"),
     *             @OA\Property(property="zip", type="string", example="abcd"),
     *             @OA\Property(property="city", type="string", example="abcd"),
     *             @OA\Property(property="state", type="string", example="abcd"),
     *             @OA\Property(property="country", type="string", example="abcd"),
     *             @OA\Property(property="country_code", type="string", example="abcd"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update store Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:stores,id',
                'name' => 'required|min:5|max:120',
                'phone' => 'required',
                'email' => 'nullable|email',
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
            $id = $request->id;
            if (!empty($request->services))
                $requestData['services'] = DataBaseResource::collection(Service::whereIn('id', $request->services)->select(['id', 'name_vi'])->get());
            if (!empty($request->foods))
                $requestData['foods'] = DataBaseResource::collection(Service::whereIn('id', $request->foods)->select(['id', 'name_vi'])->get());
            if (!empty($request->products))
                $requestData['products'] = DataBaseResource::collection(Service::whereIn('id', $request->products)->select(['id', 'name_vi'])->get());
            $data = Store::find($id);
            $data->update($requestData);
            if (!empty($request->operating_hours)) {
                // Cập nhật giờ hoạt động
                $hoursData = $request->operating_hours;
                $data->updateStoreHours($hoursData);
            }
            $data->refresh();

            return $this->sendResponse(new StoreResource($data), __('errors.STORE_UPDATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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

        try {
            \DB::table('stores')->where('id', $request->id)->update([
                'deleted_at' => now()
            ]);
            return $this->sendResponse(null, __('errors.STORE_DELETED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/store/upload",
     *     tags={"Store"},
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
                $image = Store::uploadAndResize($request->image);
            else
                $image = null;

            return $this->sendResponse($image, __('UPLOAD_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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

            return $this->sendResponse(null, __('STORE_RATING_ADD'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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

        try {
            $requestData['user_id'] = $customer->id;
            StoreRatingReply::create($requestData);
            return $this->sendResponse(null, __('STORE_RATING_REPLY'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/store/favorite/insert",
     *     tags={"Store"},
     *     summary="Favorite store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Favorite store",
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
            'id' => 'required|exists:stores,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);

        try {
            // Check if the product is already favorited by the user
            $isFavorite = \DB::table('stores_favorite')
                ->where('store_id', $request->id)
                ->where('user_id', $customer->id)
                ->exists();

            // If not favorited, insert into the database
            if (!$isFavorite) {
                \DB::table('stores_favorite')->insert([
                    'store_id' => $request->id,
                    'user_id' => $customer->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(null, __('STORE_FAVORITE_ADD'));
            } else {
                \DB::table('stores_favorite')
                    ->where('store_id', $request->id)
                    ->where('user_id', $customer->id)
                    ->delete();
                return $this->sendResponse(null, __('STORE_FAVORITE_REMOVE'));
            }

        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

}

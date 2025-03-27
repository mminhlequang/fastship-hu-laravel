<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StoreMenuResource;
use App\Http\Resources\StoreResource;
use App\Models\Category;
use App\Models\Store;
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
     *         name="is_open",
     *         in="query",
     *         description="is_open",
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
     *         name="sort_rate",
     *         in="query",
     *         description="sort_rate(asc,desc)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_distance",
     *         in="query",
     *         description="sort_distance(asc,desc)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_open",
     *         in="query",
     *         description="sort_open(asc,desc)",
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
    public function getStores(Request $request)
    {
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
        $isOpen = $request->is_open ?? null;
        $sortRate = $request->sort_rate ?? 'desc'; // Default to 'desc'
        $sortDistance = $request->sort_distance ?? 'asc'; // Default to 'asc'
        $sortOpen = $request->sort_open ?? null; // New parameter to sort by open status

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
            if ($sortRate) {
                $storesQuery->withAvg('rating', 'star') // Calculate the average star rating for each store
                ->orderBy('rating_avg_star', $sortRate); // Order by the average rating in ascending or descending order
            }

            if ($latitude && $longitude && $radius && $sortDistance) {
                $storesQuery->orderBy('distance', $sortDistance);
            }

            // Apply sorting based on open status (if sort_open is provided)
            if ($sortOpen) {
                $now = Carbon::now();
                $dayOfWeek = $now->dayOfWeek + 1;  // Get current day of the week (1-7)
                $currentTime = $now->format('H:i');  // Get current time in H:i format

                // Add the computed field for is_open and bind the parameters correctly
                $storesQuery->addSelect([
                    'stores.*',  // Select all columns from the stores table
                    \DB::raw('CASE WHEN EXISTS (
                SELECT 1
                FROM stores_hours
                WHERE stores_hours.store_id = stores.id
                  AND stores_hours.day = ' . (int) $dayOfWeek . '
                  AND stores_hours.start_time <= "' . $currentTime . '"
                  AND stores_hours.end_time >= "' . $currentTime . '"
            ) THEN 1 ELSE 0 END AS is_open')
                ]);

                // Order by the 'is_open' computed field in the desired order (ascending or descending)
                $storesQuery->orderBy('is_open', $sortOpen);
            }


            // Apply is_open filter
            if ($isOpen) {
                $now = Carbon::now();
                $dayOfWeek = $now->dayOfWeek + 1;  // Get the current day of the week
                $currentTime = $now->format('H:i');  // Get the current time in H:i format

                $storesQuery->whereHas('hours', function ($query) use ($dayOfWeek, $currentTime) {
                    $query->where('day', $dayOfWeek)
                        ->whereTime('start_time', '<=', $currentTime)
                        ->whereTime('end_time', '>=', $currentTime);
                });
            }

            // Pagination with limit and offset
            $stores = $storesQuery->skip($offset)->take($limit)->get();

            return $this->sendResponse(StoreResource::collection($stores), __('GET_STORES_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
        $active = $request->active ?? '';

        $customerId = auth('api')->id();

        try {
            $data = Store::with('creator')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name', 'like', "%$keywords%");
            })->when($active != '', function ($query) use ($active) {
                $query->where('active', $active);
            });

            $data = $data->where('creator_id', $customerId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(StoreResource::collection($data), __('GET_STORES_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
                $data = Category::with('parent')->when($storeId != 0, function ($query) use ($storeId) {
                    // Lọc theo store_id trong bảng categories_stores và sắp xếp theo 'arrange'
                    $query->join('categories_stores', 'categories.id', '=', 'categories_stores.category_id')
                        ->where('categories_stores.store_id', $storeId)
                        ->orderBy('categories_stores.arrange');  // Sắp xếp theo trường 'arrange' trong bảng categories_stores
                })
                    ->with(['products' => function ($query) use ($storeId) {
                            // Lọc các sản phẩm theo store_id
                        // Lọc sản phẩm theo store_id trong bảng categories_products (pivot table)
                        $query->where('categories_products.store_id', $storeId)
                            // Sắp xếp sản phẩm theo trường 'arrange' trong bảng categories_products
                            ->orderBy('categories_products.arrange', 'asc');
                    }])
                    ->select('categories.*') // Select fields from categories table
                    ->addSelect('categories_stores.id as categories_stores_id') // Select categories_stores ID from the pivot table
                    ->whereNull('categories.deleted_at') // Ensure the category is not deleted
                    ->orderBy('categories_stores.arrange', 'asc');
            } else {
                $data = ToppingGroup::with('toppings')->with(['toppings' => function ($query) use ($storeId) {
                    // Sắp xếp topping theo trường 'arrange' trong bảng trung gian
                    $query->orderBy('toppings_group_link.arrange', 'asc');  // Sắp xếp theo 'arrange
                }])->where('store_id', $storeId)->has('toppings');
            }
            $data = $data->skip($offset)->take($limit)->get();

            return $this->sendResponse(StoreMenuResource::collection($data), __('GET_LIST_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *             @OA\Property(property="phone", type="string", example="123456", description="SĐT"),
     *             @OA\Property(property="support_service_id", type="integer"),
     *             @OA\Property(property="support_service_additional_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Support service"),
     *             @OA\Property(property="business_type_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Business"),
     *             @OA\Property(property="category_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Thể loại"),
     *             @OA\Property(
     *                 property="banner_images",
     *                 type="array",
     *                 @OA\Items(type="string", example="storage/products/image1.webp"),
     *                 description="Các ảnh khác"
     *             ),
     *             @OA\Property(
     *                 property="contact_documents",
     *                 type="array",
     *                 @OA\Items(type="string", example="storage/products/image1.webp"),
     *                 description="Các ảnh khác"
     *             ),
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
     *             @OA\Property(property="address", type="string", example="301 HIGHLAND RD WARREN ME 04864-4177 USA"),
     *             @OA\Property(property="lat", type="double", example="123.102"),
     *             @OA\Property(property="lng", type="double", example="12.054"),
     *             @OA\Property(property="street", type="string", example="abcd"),
     *             @OA\Property(property="zip", type="string", example="abcd"),
     *             @OA\Property(property="city", type="string", example="abcd"),
     *             @OA\Property(property="state", type="string", example="abcd"),
     *             @OA\Property(property="country", type="string", example="abcd"),
     *             @OA\Property(property="country_code", type="string", example="abcd"),
     *             @OA\Property(property="contact_type", type="string", example="individual" ),
     *             @OA\Property(property="contact_full_name", type="string", example="123456"),
     *             @OA\Property(property="contact_company", type="string", example="123456"),
     *             @OA\Property(property="contact_company_address", type="string", example="123456"),
     *             @OA\Property(property="contact_phone", type="string", example="123456"),
     *             @OA\Property(property="contact_email", type="string", example="123456"),
     *             @OA\Property(property="contact_card_id", type="string", example="123456"),
     *             @OA\Property(property="contact_card_id_issue_date", type="string", example="123456"),
     *             @OA\Property(property="contact_card_id_image_front", type="string", example="123456"),
     *             @OA\Property(property="contact_card_id_image_back", type="string", example="123456"),
     *             @OA\Property(property="contact_image_license", type="string", example="123456"),
     *             @OA\Property(property="contact_tax", type="string", example="123456"),
     *             @OA\Property(property="avatar_image", type="string", example="123456"),
     *             @OA\Property(property="facade_image", type="string", example="123456"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create store Successful"),
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
                'phone' => 'required',
                'address' => 'required|max:120',
                'lat' => 'nullable',
                'lng' => 'nullable',
                'street' => 'nullable|max:120',
                'zip' => 'nullable|max:120',
                'city' => 'nullable|max:120',
                'state' => 'nullable|max:120',
                'country' => 'nullable|max:120',
                'country_code' => 'nullable|max:120',
                'operating_hours' => 'nullable|array',
                'support_service_additional_ids' => 'nullable|array',
                'business_type_ids' => 'nullable|array',
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
                ]
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            if (is_array($request->support_service_additional_ids) && !empty($request->support_service_additional_ids))
                $requestData['support_service_additional_ids'] = implode(",", $request->support_service_additional_ids);
            else
                unset($requestData['support_service_additional_ids']);

            if (is_array($request->business_type_ids) && !empty($request->business_type_ids))
                $requestData['business_type_ids'] = implode(",", $request->business_type_ids);
            else
                unset($requestData['business_type_ids']);

            $requestData['creator_id'] = auth('api')->id();

            $data = Store::create($requestData);

            if (is_array($request->category_ids) && !empty($request->category_ids)) {
                // Adding multiple categories
                $categoryIds = $request->category_ids;
                $categoryData = [];
                foreach ($categoryIds as $categoryId) {
                    $categoryData[$categoryId] = ['user_id' => auth('api')->id()]; // Add user_id to the pivot data
                }
                // Sync categories with the user_id in the pivot table
                $data->categories()->syncWithoutDetaching($categoryData);
            } else
                unset($requestData['category_ids']);

            if (is_array($request->operating_hours) && !empty($request->operating_hours)) {
                // Cập nhật giờ hoạt động
                $hoursData = $request->operating_hours;
                $data->updateStoreHours($hoursData);
            } else {
                unset($requestData['operating_hours']);
            }

            if (is_array($request->banner_images) && !empty($request->banner_images)) {
                $images = $request->banner_images;
                foreach ($images as $itemI)
                    \DB::table('stores_images')->insert([
                        'store_id' => $data->id,
                        'image' => $itemI
                    ]);
            } else {
                unset($requestData['banner_images']);
            }

            if ($request->contact_documents != null && !empty($request->contact_documents)) {
                $images = $request->contact_documents;
                foreach ($images as $itemI)
                    \DB::table('stores_documents')->insert([
                        'store_id' => $data->id,
                        'image' => $itemI
                    ]);
            } else {
                unset($requestData['contact_documents']);
            }

            \DB::commit();
            return $this->sendResponse(null, __('errors.STORE_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *             @OA\Property(property="phone", type="string", example="123456", description="SĐT"),
     *             @OA\Property(property="support_service_id", type="integer"),
     *             @OA\Property(property="support_service_additional_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Support service"),
     *             @OA\Property(property="business_type_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Business"),
     *             @OA\Property(property="category_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Thể loại"),
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
     *             @OA\Property(property="address", type="string", example="301 HIGHLAND RD WARREN ME 04864-4177 USA"),
     *             @OA\Property(property="lat", type="double", example="123.102"),
     *             @OA\Property(property="lng", type="double", example="12.054"),
     *             @OA\Property(property="street", type="string", example="abcd"),
     *             @OA\Property(property="zip", type="string", example="abcd"),
     *             @OA\Property(property="city", type="string", example="abcd"),
     *             @OA\Property(property="state", type="string", example="abcd"),
     *             @OA\Property(property="country", type="string", example="abcd"),
     *             @OA\Property(property="country_code", type="string", example="abcd"),
     *             @OA\Property(property="contact_type", type="string", example="individual" ),
     *             @OA\Property(property="contact_full_name", type="string", example="123456"),
     *             @OA\Property(property="contact_company", type="string", example="123456"),
     *             @OA\Property(property="contact_company_address", type="string", example="123456"),
     *             @OA\Property(property="contact_phone", type="string", example="123456"),
     *             @OA\Property(property="contact_email", type="string", example="123456"),
     *             @OA\Property(property="contact_card_id", type="string", example="123456"),
     *             @OA\Property(property="contact_card_id_issue_date", type="string", example="123456"),
     *             @OA\Property(property="contact_card_id_image_front", type="string", example="123456"),
     *             @OA\Property(property="contact_card_id_image_back", type="string", example="123456"),
     *             @OA\Property(property="contact_image_license", type="string", example="123456"),
     *             @OA\Property(property="contact_tax", type="string", example="123456"),
     *             @OA\Property(property="avatar_image", type="string", example="123456"),
     *             @OA\Property(property="facade_image", type="string", example="123456"),
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
                'name' => 'nullable|max:120',
                'phone' => 'nullable|max:50',
                'email' => 'nullable|email',
                'address' => 'nullable|max:120',
                'lat' => 'nullable',
                'lng' => 'nullable',
                'street' => 'nullable|max:120',
                'zip' => 'nullable|max:120',
                'city' => 'nullable|max:120',
                'state' => 'nullable|max:120',
                'country' => 'nullable|max:120',
                'country_code' => 'nullable|max:120',
                'operating_hours' => 'nullable|array',
                'support_service_additional_ids' => 'nullable|array',
                'business_type_ids' => 'nullable|array',
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
                ]
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $id = $request->id;
            if (is_array($request->support_service_additional_ids) && !empty($request->support_service_additional_ids))
                $requestData['support_service_additional_ids'] = implode(",", $request->support_service_additional_ids);
            else
                unset($requestData['support_service_additional_ids']);

            if (is_array($request->business_type_ids) && !empty($request->business_type_ids))
                $requestData['business_type_ids'] = implode(",", $request->business_type_ids);
            else
                unset($requestData['business_type_ids']);

            $data = Store::find($id);
            $data->update($requestData);
            $data->refresh();

            if (!empty($request->operating_hours)) {
                // Cập nhật giờ hoạt động
                $hoursData = $request->operating_hours;
                $data->updateStoreHours($hoursData);
            }

            if (is_array($request->category_ids) && !empty($request->category_ids)) {
                // Adding multiple categories
                $categoryIds = $request->category_ids;
                $categoryData = [];
                foreach ($categoryIds as $categoryId) {
                    $categoryData[$categoryId] = ['user_id' => auth('api')->id()]; // Add user_id to the pivot data
                }
                // Sync categories with the user_id in the pivot table
                $data->categories()->sync($categoryData);
            } else
                unset($requestData['category_ids']);


            \DB::commit();

            return $this->sendResponse(new StoreResource($data), __('errors.STORE_UPDATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
        \DB::beginTransaction();
        try {
            $id = $request->id;
            \DB::table('stores')->where('id', $id)->update([
                'deleted_at' => now()
            ]);
            \DB::table('categories_stores')->where('store_id', $id)->delete();
            \DB::commit();
            return $this->sendResponse(null, __('errors.STORE_DELETED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
                $image = Store::uploadAndResize($request->image);
            else
                $image = null;

            return $this->sendResponse($image, __('UPLOAD_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     * @OA\Post(
     *     path="/api/v1/store/sort_categproes",
     *     tags={"Store"},
     *     summary="Sort categories",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sort categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="category_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Danh sách category theo thứ tự"),
     *             @OA\Property(property="store_id", type="integer", example="1", description="Id store"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sort successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function sortCategories(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'category_ids' => 'required|array',
            'store_id' => 'required|exists:stores,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $storeId = $request->store_id;
            $categoryIds = $request->category_ids;
            foreach ($categoryIds as $keyC => $itemC) {
                \DB::table('categories_stores')->where([['category_id', $itemC], ['store_id', $storeId]])->update([
                    'arrange' => ++$keyC
                ]);
            }
            \DB::commit();
            return $this->sendResponse(null, __('CATEGORY_SORTED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/store/sort_toppings",
     *     tags={"Store"},
     *     summary="Sort toppings",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sort toppings",
     *         @OA\JsonContent(
     *             @OA\Property(property="topping_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Danh sách topping theo thứ tự"),
     *             @OA\Property(property="group_id", type="integer", example="1", description="Id nhóm topping"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sort successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function sortToppings(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'topping_ids' => 'required|array',
            'group_id' => 'required|exists:toppings_group,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $groupId = $request->group_id;
            $productIds = $request->topping_ids;
            foreach ($productIds as $keyC => $itemC) {
                \DB::table('toppings_group_link')->where([['group_id', $groupId], ['topping_id', $itemC]])->update([
                    'arrange' => ++$keyC
                ]);
            }
            \DB::commit();
            return $this->sendResponse(null, __('TOPPING_SORTED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/store/sort_products",
     *     tags={"Store"},
     *     summary="Sort products",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sort products",
     *         @OA\JsonContent(
     *             @OA\Property(property="product_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Danh sách product theo thứ tự"),
     *             @OA\Property(property="store_id", type="integer", example="1", description="Id store"),
     *             @OA\Property(property="category_id", type="integer", example="1", description="Id category"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sort successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function sortProducts(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'product_ids' => 'required|array',
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $storeId = $request->store_id;
            $categoryId = $request->category_id;
            $productIds = $request->product_ids;
            foreach ($productIds as $keyC => $itemC) {
                \DB::table('categories_products')->where([['category_id', $categoryId], ['store_id', $storeId], ['product_id', $itemC]])->update([
                    'arrange' => ++$keyC
                ]);
            }
            \DB::commit();
            return $this->sendResponse(null, __('PRODUCT_SORTED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

}

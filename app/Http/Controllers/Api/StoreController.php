<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StoreResource;
use App\Models\AddressDelivery;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\Request;
use Validator;


class StoreController extends BaseController
{

    /**
     * @OA\SecurityScheme(
     *     securityScheme="Bearer",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     description="Enter your Bearer token below"
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/v1/store",
     *     tags={"Store"},
     *     summary="Get all store",
     *     security={{"Bearer": {}}},
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
     *     @OA\Response(response="200", description="Get all stores")
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
     *     security={{"bearerAuth":{}}},
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
     *     @OA\Response(response="200", description="Get all stores")
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
     *     summary="Get detail address by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID of the address",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found"
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
            'id' => 'required|exits:stores,id',
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
        if (!$customer)
            return $this->sendError("Invalid signature");
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:5|max:120',
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
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
            ],
            [
                'name.required' => 'Tên club bắt buộc phải có',
                'name.min' => 'Tên club tối thiểu 5 kí tự',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Store::uploadAndResize($request->file('image'));

            $requestData['customer_id'] = $customer->id;

            $data = AddressDelivery::create($requestData);

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
     *         description="Club object that needs to be update",
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
        if (!$customer)
            return $this->sendError("Invalid signature");
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exits:stores,id',
                'name' => 'required|min:5|max:120',
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
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
            ],
            [
                'name.min' => 'Tên cửa hàng tối thiểu 5 kí tự',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Store::uploadAndResize($request->file('image'));

            $data = Store::find($requestData['id']);

            $data->update($requestData);

            $data->refresh();

            return $this->sendResponse(new StoreResource($data), __('api_store_updated'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


}

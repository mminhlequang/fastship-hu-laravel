<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\AddressDeliveryResource;
use App\Models\AddressDelivery;
use App\Models\Customer;
use Illuminate\Http\Request;
use Validator;


class AddressDeliveryController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/address_delivery/get_my_address",
     *     tags={"Address Delivery"},
     *     summary="Get all address",
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
     *     @OA\Response(response="200", description="Get all address"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? '';

        $customerId = auth('api')->id();

        try {
            $data = AddressDelivery::with('customer')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name', 'like', "%$keywords%");
            });

            $data = $data->where('customer_id', $customerId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(AddressDeliveryResource::collection($data), 'Get all address successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/address_delivery/detail",
     *     tags={"Address Delivery"},
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
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function detail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id' => 'required|exists:address_delivery,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $data = AddressDelivery::find($requestData['id']);

            return $this->sendResponse(new AddressDeliveryResource($data), "Get detail successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/address_delivery/delete",
     *     tags={"Address Delivery"},
     *     summary="Delete address",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete address",
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
            'id' => 'required|exists:address_delivery,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            \DB::table('address_delivery')->where('id', $request->id)->update([
                'deleted_at' => now()
            ]);

            return $this->sendResponse(null, __('api.address_deleted'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/address_delivery/create",
     *     tags={"Address Delivery"},
     *     summary="Create address",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Address object that needs to be created",
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
     *             @OA\Property(property="is_default", type="integer", example="0"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create address Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function create(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:5|max:120',
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
                'is_default' => 'nullable|in:0,1',
            ],
            [
                'name.required' => 'Tên địa chỉ bắt buộc phải có',
                'name.min' => 'Tên địa chỉ tối thiểu 5 kí tự',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $requestData['customer_id'] = auth('api')->id();

            $isDefault = $request->is_default ?? 0;

            //Set all address is_default 0
            if ($isDefault == 1) \DB::table('address_delivery')->where('customer_id', auth('api')->id())->update([
                'is_default' => 0
            ]);

            $data = AddressDelivery::create($requestData);

            return $this->sendResponse(new AddressDelivery($data), __('api.address_created'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/address_delivery/update",
     *     tags={"Address Delivery"},
     *     summary="Update Address",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Address object that needs to be update",
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
     *             @OA\Property(property="is_default", type="integer", example="0"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update address Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:address_delivery,id',
                'name' => 'required|min:5|max:120',
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
                'is_default' => 'nullable|in:0,1',
            ],
            [
                'name.min' => 'Tên address tối thiểu 5 kí tự',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $isDefault = $request->is_default ?? 0;

            //Set all address is_default 0
            if ($isDefault == 1) \DB::table('address_delivery')->where('customer_id', auth('api')->id())->update([
                'is_default' => 0
            ]);

            $data = AddressDelivery::find($requestData['id']);

            $data->update($requestData);

            $data->refresh();

            return $this->sendResponse(new AddressDeliveryResource($data), __('api_address_updated'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


}

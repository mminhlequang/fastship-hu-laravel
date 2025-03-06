<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\VoucherResource;
use App\Models\Customer;
use App\Models\Discount;
use Illuminate\Http\Request;
use Validator;

class VoucherController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/voucher",
     *     tags={"Voucher"},
     *     summary="Get all vouchers by store",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="store_id",
     *         required=false,
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
     *     @OA\Response(response="200", description="Get all vouchers by store")
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        $storeId = $request->store_id ?? 0;
        try {
            $data = Discount::when($keywords != '', function ($query) use ($keywords) {
                $query->where('code', 'like', "%$keywords%");
            })->where('store_id', $storeId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(VoucherResource::collection($data), 'Get all vouchers successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/voucher/by_user",
     *     tags={"Voucher"},
     *     summary="Get all vouchers by store",
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
     *     @OA\Response(response="200", description="Get all vouchers by store"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getListByUser(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";

        $customer = Customer::getAuthorizationUser($request);


        try {
            $ids = \DB::table('discounts_user')->where('user_id', $customer->id)->pluck('discount_id')->toArray();

            $data = Discount::when($keywords != '', function ($query) use ($keywords) {
                $query->where('code', 'like', "%$keywords%");
            })->whereIn('id', $ids)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(VoucherResource::collection($data), 'Get all vouchers successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }



    /**
     * @OA\Post(
     *     path="/api/v1/voucher/create",
     *     tags={"Voucher"},
     *     summary="Create voucher",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Voucher object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="code", type="string", example="VOUCHER50", description="Mã giảm giá."),
     *          @OA\Property(property="name", type="string", example="Mã giảm giá shopA", description="Tên mã giảm giá."),
     *          @OA\Property(property="image", type="string", example="abcd", description="Đường dẫn đến hình ảnh của sản phẩm hoặc mã giảm giá."),
     *          @OA\Property(property="cart_value", type="double", example="10000", description="Giá trị của đơn hàng để áp dụng mã giảm giá."),
     *          @OA\Property(property="sale_maximum", type="double", example="5000", description="Giảm giá tối đa có thể áp dụng."),
     *          @OA\Property(property="description", type="string", example="abcd", description="Mô tả chi tiết về sản phẩm hoặc mã giảm giá."),
     *          @OA\Property(property="product_ids", type="string", example="1,2,3", description="Danh sách sản phẩm áp dụng. để null nếu áp dụng cho tất cả sản phẩm"),
     *          @OA\Property(property="value", type="integer", example="0", description="Giá trị giảm giá. 0 có thể nghĩa là không có giảm giá."),
     *          @OA\Property(property="start_date", type="date", example="2025-06-18", description="Ngày bắt đầu hiệu lực của mã giảm giá."),
     *          @OA\Property(property="expiry_date", type="date", example="2025-06-18", description="Ngày hết hạn của mã giảm giá."),
     *          @OA\Property(property="type", type="integer", example="1", description="Loại giảm giá. 1: giảm %, 2: giảm trực tiếp."),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng áp dụng mã giảm giá."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create voucher Successful"),
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
                'code' => 'required|max:120',
                'name' => 'required|max:120',
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'cart_value' => 'required',
                'description' => 'required|max:3000',
                'product_ids' => 'required|max:120',
                'value' => 'required',
                'start_date' => 'required|date_format:Y-m-d|required_without:expiry_date',
                'expiry_date' => 'required|date_format:Y-m-d|required_without:start_date',
                'type' => 'required|in:1,2',
                'store_id' => 'required|exists:stores,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Discount::uploadAndResize($request->file('image'));

            $requestData['user_id'] = $customer->id;

            $data = Discount::create($requestData);

            return $this->sendResponse(new VoucherResource($data), __('errors.VOUCHER_CREATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/voucher/update",
     *     tags={"Voucher"},
     *     summary="Update voucher",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Voucher object that needs to be update",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID mã giảm giá"),
     *          @OA\Property(property="code", type="string", example="VOUCHER50", description="Mã giảm giá."),
     *          @OA\Property(property="name", type="string", example="Mã giảm giá shopA", description="Tên mã giảm giá."),
     *          @OA\Property(property="image", type="string", example="abcd", description="Đường dẫn đến hình ảnh của sản phẩm hoặc mã giảm giá."),
     *          @OA\Property(property="cart_value", type="double", example="10000", description="Giá trị của đơn hàng để áp dụng mã giảm giá."),
     *          @OA\Property(property="sale_maximum", type="double", example="5000", description="Giảm giá tối đa có thể áp dụng."),
     *          @OA\Property(property="description", type="string", example="abcd", description="Mô tả chi tiết về sản phẩm hoặc mã giảm giá."),
     *          @OA\Property(property="product_ids", type="string", example="1,2,3", description="Danh sách sản phẩm áp dụng. để null nếu áp dụng cho tất cả sản phẩm"),
     *          @OA\Property(property="value", type="integer", example="0", description="Giá trị giảm giá. 0 có thể nghĩa là không có giảm giá."),
     *          @OA\Property(property="start_date", type="date", example="2025-06-18", description="Ngày bắt đầu hiệu lực của mã giảm giá."),
     *          @OA\Property(property="expiry_date", type="date", example="2025-06-18", description="Ngày hết hạn của mã giảm giá."),
     *          @OA\Property(property="type", type="integer", example="1", description="Loại giảm giá. 1: giảm %, 2: giảm trực tiếp."),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng áp dụng mã giảm giá."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update voucher Successful"),
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
                'id' => 'required|exists:discounts,id',
                'code' => 'required|max:120',
                'name' => 'required|max:120',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'cart_value' => 'required',
                'description' => 'required|max:3000',
                'product_ids' => 'required|max:120',
                'value' => 'required',
                'start_date' => 'required|date_format:Y-m-d|required_without:expiry_date',
                'expiry_date' => 'required|date_format:Y-m-d|required_without:start_date',
                'type' => 'required|in:1,2',
                'store_id' => 'required|exists:stores,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Discount::uploadAndResize($request->file('image'));

            $data = Discount::find($requestData['id']);

            $data->update($requestData);

            $data->refresh();

            return $this->sendResponse(new VoucherResource($data), __('errors.VOUCHER_UPDATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/voucher/save",
     *     tags={"Voucher"},
     *     summary="Save voucher by user",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Save voucher by user",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1", description="ID của voucher"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Save successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function save(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id' => 'required|exists:discounts,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);

        try {
            // Check if the product is already favorited by the user
            $isSave = \DB::table('discounts_user')
                ->where('discount_id', $request->id)
                ->where('user_id', $customer->id)
                ->exists();

            // If not favorited, insert into the database
            if (!$isSave) {
                \DB::table('discounts_user')->insert([
                    'discount_id' => $request->id,
                    'user_id' => $customer->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(null, __('errors.VOUCHER_SAVE_REMOVE'));
            } else {
                \DB::table('discounts_user')
                    ->where('discount_id', $request->id)
                    ->where('user_id', $customer->id)
                    ->delete();
                return $this->sendResponse(null, __('errors.VOUCHER_SAVE_ADD'));
            }
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/voucher/delete",
     *     tags={"Voucher"},
     *     summary="Delete voucher",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete voucher",
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
            'id' => 'required|exists:discounts,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);

        try {
            \DB::table('discounts')->where('id', $request->id)->update([
                'deleted_at' => now()
            ]);
            return $this->sendResponse(null, __('errors.VOUCHER_DELETED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


}

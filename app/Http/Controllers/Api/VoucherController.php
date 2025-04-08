<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\VoucherResource;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Discount;
use Illuminate\Http\Request;
use Validator;

class VoucherController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/voucher/get_vouchers",
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
     *     @OA\Response(response="200", description="Get all vouchers by store"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getVouchers(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        $storeId = $request->store_id ?? 0;

        try {
            $userId = auth('api')->id();

            $cartValue = CartItem::whereHas('cart', function ($query) use ($userId, $storeId) {
                $query->where([['user_id', $userId], ['store_id', $storeId]]);
            })->sum('price'); // This will sum the 'price' field in all cart items for the user

            $data = Discount::when($keywords != '', function ($query) use ($keywords) {
                $query->where('code', 'like', "%$keywords%");
            })->where(function ($query) use ($userId) {
                $query->where('user_id', null)  // Lấy các voucher dành cho tất cả người dùng
                ->orWhere('user_id', $userId);  // Lấy các voucher dành riêng cho user này
            })->whereDoesntHave('users', function ($query) use ($userId) {
                // Lọc các voucher đã được sử dụng bởi user (tức là có liên kết trong bảng voucher_user)
                $query->where('user_id', $userId);
            })->where('store_id', $storeId)->whereNull('deleted_at')
                // Add the sorting by is_valid DESC here
//                ->orderByRaw('ISNULL(is_valid) DESC, is_valid DESC')  // Ensuring that NULL values are placed at the bottom, if applicable
                ->latest()->skip($offset)->take($limit)->get();

            // Add the 'is_valid' field to each voucher based on conditions
            $data->map(function ($voucher) use ($cartValue, $userId, $storeId) {
                // Check if the voucher is valid: based on cart value, product_ids, and the date range
                $isValid = $cartValue >= $voucher->cart_value && now()->between($voucher->start_date, $voucher->expiry_date);

                // If product_ids is null, apply to all products
                if ($voucher->product_ids === null) {
                    $voucher->is_valid = $isValid ? 1 : 0;
                } else {
                    // If product_ids is not null, check if any product in the cart matches the voucher's product_ids
                    $productIds = explode(',', $voucher->product_ids); // Convert product_ids string to an array of ids
                    $cartItems = CartItem::whereHas('cart', function ($query) use ($userId, $storeId) {
                        $query->where([['user_id', $userId], ['store_id', $storeId]]);
                    })->pluck('product_id')->toArray(); // Get product ids from cart_items for the user

                    // Check if any cart item product_id matches the voucher's product_ids
                    $isValidForProducts = !empty(array_intersect($productIds, $cartItems));

                    // Set is_valid based on both cart value, date range, and product matching
                    $voucher->is_valid = ($isValid && $isValidForProducts) ? 1 : 0;
                }

                return $voucher;
            });

            return $this->sendResponse(VoucherResource::collection($data), __('GET_VOUCHERS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/voucher/get_vouchers_user_saved",
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

        try {
            $ids = \DB::table('discounts_user')->where('user_id', auth('api')->id())->pluck('discount_id')->toArray();

            $data = Discount::when($keywords != '', function ($query) use ($keywords) {
                $query->where('code', 'like', "%$keywords%");
            })->whereIn('id', $ids)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(VoucherResource::collection($data), 'Get all vouchers successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *          @OA\Property(property="type", type="string", example="fixed", description="percentage: giảm %, fixed: giảm trực tiếp."),
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

        $validator = Validator::make(
            $request->all(),
            [
                'code' => 'required|max:120',
                'name' => 'required|max:120',
                'cart_value' => 'required',
                'description' => 'required|max:3000',
                'product_ids' => 'required|max:120',
                'value' => 'required',
                'start_date' => 'required|date_format:Y-m-d|required_without:expiry_date',
                'expiry_date' => 'required|date_format:Y-m-d|required_without:start_date',
                'type' => 'required|in:fixed,percentage',
                'store_id' => 'required|exists:stores,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Discount::uploadAndResize($request->file('image'));

            $requestData['user_id'] = auth('api')->id();
            $requestData['creator_id'] = auth('api')->id();

            $data = Discount::create($requestData);

            return $this->sendResponse(new VoucherResource($data), __('VOUCHER_CREATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *          @OA\Property(property="type", type="string", example="fixed", description="percentage: giảm %, fixed: giảm trực tiếp."),
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

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:discounts,id',
                'code' => 'nullable|max:120',
                'name' => 'nullable|max:120',
                'cart_value' => 'nullable',
                'description' => 'nullable|max:3000',
                'product_ids' => 'nullable|max:120',
                'value' => 'required',
                'start_date' => 'nullable|date_format:Y-m-d|required_without:expiry_date',
                'expiry_date' => 'nullable|date_format:Y-m-d|required_without:start_date',
                'type' => 'nullable|in:fixed,percentage',
                'store_id' => 'nullable|exists:stores,id',
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

            return $this->sendResponse(new VoucherResource($data), __('VOUCHER_UPDATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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

        try {
            // Check if the product is already favorited by the user
            $isSave = \DB::table('discounts_user')
                ->where('discount_id', $request->id)
                ->where('user_id', auth('api')->id())
                ->exists();

            // If not favorited, insert into the database
            if (!$isSave) {
                \DB::table('discounts_user')->insert([
                    'discount_id' => $request->id,
                    'user_id' => auth('api')->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $this->sendResponse(1, __('VOUCHER_SAVE_ADD'));
            } else {
                \DB::table('discounts_user')
                    ->where('discount_id', $request->id)
                    ->where('user_id', auth('api')->id())
                    ->delete();
                return $this->sendResponse(0, __('VOUCHER_SAVE_REMOVE'));
            }
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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

        try {
            \DB::table('discounts')->where('id', $request->id)->update([
                'deleted_at' => now()
            ]);
            return $this->sendResponse(null, __('VOUCHER_DELETED'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/voucher/check_voucher",
     *     tags={"Voucher"},
     *     summary="Check voucher",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Check voucher",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="abc"),
     *             @OA\Property(property="store_id", type="integer", example="1", description="Id store"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Voucher successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function checkVoucher(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'code' => 'required|exists:discounts,code',
            'store_id' => 'required|exists:stores,id'
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $userId = auth('api')->id();
            $storeId = $request->store_id;

            // Calculate the total cart value by summing the 'price' field in the 'cart_items' table
            $cartValue = CartItem::whereHas('cart', function ($query) use ($userId, $storeId) {
                $query->where([['user_id', $userId], ['store_id', $storeId]]);
            })->sum('price'); // This will sum the 'price' field in all cart items for the user

            $voucher = Discount::where('code', $request->code)
                ->where('active', true)
                ->whereDate('start_date', '<=', now())
                ->whereDate('expiry_date', '>=', now())
                ->first();

            if (!$voucher) return $this->sendError(__('VOUCHER_NOT_VALID'));

            // Kiểm tra giá trị đơn hàng có đủ điều kiện để áp dụng voucher
            if ($cartValue < $voucher->cart_value)
                return $this->sendError(__('VOUCHER_NOT_ENOUGH_VALUE_ORDER'));

            // If product_ids is not null, check if any cart item matches
            if ($voucher->product_ids !== null) {
                $productIds = explode(',', $voucher->product_ids); // Convert product_ids string to an array of ids
                $cartItems = CartItem::whereHas('cart', function ($query) use ($userId, $storeId) {
                    $query->where([['user_id', $userId], ['store_id', $storeId]]);
                })->pluck('product_id')->toArray(); // Get product ids from cart_items for the user

                // Check if any product in the cart matches the voucher's product_ids
                $matchingProducts = array_intersect($productIds, $cartItems);

                if (empty($matchingProducts)) {
                    return $this->sendError(__('VOUCHER_NO_MATCHING_PRODUCTS'));
                }
            }

            // Tính toán giá trị giảm giá
            $value = $this->calculateDiscount($voucher, $cartValue);

            return $this->sendResponse([
                'voucher' => new VoucherResource($voucher),
                'value' => $value
            ], __('VOUCHER_VALID'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    private function calculateDiscount($voucher, $cartValue)
    {
        if ($voucher->type == 'percentage') {
            // Giảm giá theo tỷ lệ phần trăm
            $discount = ($voucher->value / 100) * $cartValue;
            // Giới hạn mức giảm tối đa
            return min($discount, $voucher->sale_maximum);
        } else {
            // Giảm giá cố định
            return min($voucher->value, $voucher->sale_maximum);
        }
    }


}

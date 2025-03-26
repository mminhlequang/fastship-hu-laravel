<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ToppingGroupResource;
use App\Http\Resources\ToppingResource;
use App\Models\Customer;
use App\Models\ToppingGroup;
use Illuminate\Http\Request;
use Validator;

class ToppingGroupController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/group/get_my_stores",
     *     tags={"Topping"},
     *     summary="Get all group topping",
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
     *     @OA\Response(response="200", description="Get all group topping"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function getMyStores(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        $storeId = $request->store_id ?? 0;

        try {
            $data = ToppingGroup::with('store')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            })->where('store_id', $storeId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(ToppingGroupResource::collection($data), 'Get all topping successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/group/create",
     *     tags={"Topping"},
     *     summary="Create group topping",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Group topping object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="name", type="string", example="Name vi", description="Tên"),
     *          @OA\Property(property="topping_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Topping liên kết"),
     *          @OA\Property(property="product_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Món liên kết"),
     *          @OA\Property(property="variation_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Biến thể"),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng."),
     *          @OA\Property(property="max_quantity", type="integer", example="10", description="Số lượng tối đa(set 0 nếu ko bắt buộc)"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create group topping Successful"),
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
                'store_id' => 'required|exists:stores,id',
                'topping_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $toppingId) {
                                if (!\DB::table('toppings')->where('id', $toppingId)->exists()) {
                                    $fail("The topping ID $toppingId does not exist.");
                                }
                            }
                        }
                    },
                ],
                'product_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $productId) {
                                if (!\DB::table('products')->where('id', $productId)->exists()) {
                                    $fail("The product ID $productId does not exist.");
                                }
                            }
                        }
                    },
                ],
                'variation_ids' => 'nullable|array',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $requestData['creator_id'] = auth('api')->id();

            $data = ToppingGroup::create($requestData);

            // Lấy mảng topping_ids từ chuỗi
            if (is_array($request->topping_ids) && !empty($request->topping_ids)) {
                $toppingIds = $request->topping_ids;
                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($toppingIds as $toppingId) {
                    \DB::table('toppings_group_link')->insert([
                        'topping_id' => $toppingId,
                        'group_id' => $data->id,
                    ]);
                }
            } else {
                unset($requestData['topping_ids']);
            }


            // Lấy mảng product_ids từ chuỗi
            if (is_array($request->product_ids) && !empty($request->product_ids)) {
                $productIds = $request->product_ids;
                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($productIds as $productId) {
                    \DB::table('products_groups')->insert([
                        'group_id' => $data->id,
                        'product_id' => $productId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                unset($requestData['product_ids']);
            }


            // Lấy mảng variation_ids từ chuỗi
            if (is_array($request->variation_ids) && !empty($request->variation_ids)) {
                $variationIds = $request->variation_ids;
                foreach ($variationIds as $variationId) {
                    \DB::table('variation_group')->insert([
                        'group_id' => $data->id,
                        'variation_id' => $variationId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                unset($requestData['variation_ids']);
            }

            \DB::commit();
            return $this->sendResponse(new ToppingGroupResource($data), __('errors.TOPPING_GROUP_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/group/update",
     *     tags={"Topping"},
     *     summary="Update group topping",
     *     @OA\RequestBody(
     *         required=true,
     *         description="topping object that needs to be update",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID thể loại"),
     *          @OA\Property(property="name", type="string", example="Name vi", description="Tên"),
     *          @OA\Property(property="topping_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Topping liên kết"),
     *          @OA\Property(property="product_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Món liên kết"),
     *          @OA\Property(property="variation_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Biến thể"),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng."),
     *          @OA\Property(property="max_quantity", type="integer", example="10", description="Số lượng tối đa(set 0 nếu ko bắt buộc)"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update group topping Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:topping,id',
                'name' => 'nullable|max:120',
                'store_id' => 'nullable|exists:stores,id',
                'topping_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $toppingId) {
                                if (!\DB::table('toppings')->where('id', $toppingId)->exists()) {
                                    $fail("The topping ID $toppingId does not exist.");
                                }
                            }
                        }
                    },
                ],
                'product_ids' => [
                    'nullable',        // This allows the field to be null
                    'array',           // This ensures the field is an array
                    function ($attribute, $value, $fail) {
                        // Custom validation to ensure each ID exists in the products table
                        if ($value && is_array($value)) {
                            foreach ($value as $productId) {
                                if (!\DB::table('products')->where('id', $productId)->exists()) {
                                    $fail("The product ID $productId does not exist.");
                                }
                            }
                        }
                    },
                ],
                'variation_ids' => 'nullable|array',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        \DB::beginTransaction();
        try {
            $id = $request->id;

            $data = ToppingGroup::find($id);

            $data->update($requestData);

            $data->refresh();

            // Lấy mảng topping_ids từ chuỗi
            if (is_array($request->topping_ids) && !empty($request->topping_ids)) {
                $toppingIds = $request->topping_ids;
                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($toppingIds as $toppingId) {
                    // Kiểm tra xem cặp topping_id và group_id đã tồn tại chưa
                    $exists = \DB::table('toppings_group_link')
                        ->where('topping_id', $toppingId)
                        ->where('group_id', $id)
                        ->exists(); // Trả về true nếu đã tồn tại, false nếu chưa có

                    // Nếu chưa tồn tại, tiến hành insert
                    if (!$exists) {
                        \DB::table('toppings_group_link')->insert([
                            'topping_id' => $toppingId,
                            'group_id' => $id
                        ]);
                    }
                }
                // Xoá các topping_id không có trong mảng toppingIds
                \DB::table('toppings_group_link')
                    ->where('group_id', $id)
                    ->whereNotIn('topping_id', $toppingIds)  // Kiểm tra nếu product_id không có trong mảng
                    ->delete(); // Xoá các bản ghi không có trong productIds
            } else {
                unset($requestData['topping_ids']);
            }

            // Lấy mảng product_ids từ chuỗi
            if (is_array($request->product_ids) && !empty($request->product_ids)) {
                $productIds = $request->product_ids;
                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($productIds as $productId) {
                    // Kiểm tra xem cặp topping_id và group_id đã tồn tại chưa
                    $exists = \DB::table('products_groups')
                        ->where('product_id', $productId)
                        ->where('group_id', $id)
                        ->exists(); // Trả về true nếu đã tồn tại, false nếu chưa có

                    if (!$exists) {
                        \DB::table('products_groups')->insert([
                            'group_id' => $id,
                            'product_id' => $productId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                }
                // Xoá các product_id không có trong mảng productIds
                \DB::table('products_groups')
                    ->where('group_id', $id)
                    ->whereNotIn('product_id', $productIds)  // Kiểm tra nếu product_id không có trong mảng
                    ->delete(); // Xoá các bản ghi không có trong productIds
            } else {
                unset($requestData['product_ids']);
            }

            // Lấy mảng variation_ids từ chuỗi
            if (is_array($request->variation_ids) && !empty($request->variation_ids)) {
                $variationIds = $request->variation_ids;
                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($variationIds as $variationId) {
                    // Kiểm tra xem cặp topping_id và group_id đã tồn tại chưa
                    $exists = \DB::table('variation_group')
                        ->where('variation_id', $variationId)
                        ->where('group_id', $id)
                        ->exists(); // Trả về true nếu đã tồn tại, false nếu chưa có

                    // Nếu chưa tồn tại, tiến hành insert
                    if (!$exists) {
                        \DB::table('variation_group')->insert([
                            'variation_id' => $variationId,
                            'group_id' => $id
                        ]);
                    }
                }
                // Xoá các variation_id không có trong mảng toppingIds
                \DB::table('variation_group')
                    ->where('group_id', $id)
                    ->whereNotIn('variation_id', $variationIds)  // Kiểm tra nếu product_id không có trong mảng
                    ->delete(); // Xoá các bản ghi không có trong productIds
            } else {
                unset($requestData['variation_ids']);
            }

            \DB::commit();
            return $this->sendResponse(new ToppingResource($data), __('errors.TOPPING_GROUP_UPDATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/group/delete",
     *     tags={"Topping"},
     *     summary="Delete group topping",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete group topping",
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
            'id' => 'required|exists:toppings_group,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $id = $request->id;
            \DB::table('toppings_group')->where('id', $id)->update([
                'deleted_at' => now()
            ]);
            //Delete link group topping
            \DB::table('toppings_group_link')->where('group_id', $id)->delete();

            \DB::commit();
            return $this->sendResponse(null, __('errors.TOPPING_GROUP_DELETED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


}

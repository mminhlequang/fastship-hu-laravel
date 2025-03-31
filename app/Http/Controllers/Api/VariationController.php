<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\VariationResource;
use App\Models\Customer;
use App\Models\Variation;
use Illuminate\Http\Request;
use Validator;

class VariationController extends BaseController
{


    /**
     * @OA\Post(
     *     path="/api/v1/variation/create",
     *     tags={"Topping"},
     *     summary="Create variation",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Variation object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="name", type="string", example="Độ ngọt", description="Tên biến thể"),
     *          @OA\Property(property="values", type="array", @OA\Items(
     *            @OA\Property(property="value", type="string", example="100%"),
     *            @OA\Property(property="price", type="integer", example="0")
     *          ), description="Giá trị options(biến thể)"),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create variation Successful"),
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
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {

            $requestData['creator_id'] = auth('api')->id();

            $data = Variation::create($requestData);

            // Lấy mảng product_ids từ chuỗi
            if (is_array($request->values) && !empty($request->values)) {
                $variationValues = $request->values;
                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($variationValues as $itemV) {
                    \DB::table('variation_values')->insert([
                        'variation_id' => $data->id,
                        'value' => $itemV->value,
                        'price' => $itemV->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                unset($requestData['values']);
            }

            \DB::commit();
            return $this->sendResponse(new VariationResource($data), __('VARIATION_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/variation/update",
     *     tags={"Topping"},
     *     summary="Update variation",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Variation object that needs to be update",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID option(biến thể)"),
     *          @OA\Property(property="name", type="string", example="Độ ngọt", description="Tên biến thể"),
     *          @OA\Property(property="arrange", type="integer"),
     *          @OA\Property(property="is_default", type="integer"),
     *          @OA\Property(property="is_active", type="integer"),
     *          @OA\Property(property="values", type="array", @OA\Items(
     *            @OA\Property(property="value", type="string", example="100%"),
     *            @OA\Property(property="price", type="integer", example="0")
     *          ), description="Giá trị options(biến thể)"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update variation Successful"),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function update(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:variations,id',
                'name' => 'nullable|max:120'
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        \DB::beginTransaction();
        try {
            $id = $request->id;
            $data = Variation::find($id);

            // Check if the current `is_default` value is 1 (if you're updating)
            $isDefault = $request->is_default ?? 0;

            //Set all address is_default 0
            if ($isDefault == 1) \DB::table('variations')->where('store_id', $data->store_id)->update([
                'is_default' => 0
            ]);

            $data->update($requestData);

            $data->refresh();

            // Lấy mảng topping_ids từ chuỗi
            if (is_array($request->values) && !empty($request->values)) {
                $variationValues = $request->values;
                // First, delete any entries that are not present in $variationValues
                \DB::table('variation_values')
                    ->where('variation_id', $id)
                    ->whereNotIn('value', collect($variationValues)->pluck('value'))
                    ->whereNotIn('price', collect($variationValues)->pluck('price'))
                    ->delete();

                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($variationValues as $itemV) {
                    // Kiểm tra xem cặp topping_id và group_id đã tồn tại chưa
                    $exists = \DB::table('variation_values')
                        ->where('variation_id', $id)
                        ->where('value', $itemV->value)
                        ->where('price', $itemV->price)
                        ->exists(); // Trả về true nếu đã tồn tại, false nếu chưa có

                    if (!$exists) {
                        \DB::table('variation_values')->insert([
                            'variation_id' => $data->id,
                            'value' => $itemV->value,
                            'price' => $itemV->price,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }else {
                unset($requestData['values']);
            }

            \DB::commit();
            return $this->sendResponse(new VariationResource($data), __('VARIATION_UPDATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/variation/delete",
     *     tags={"Topping"},
     *     summary="Delete variation",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete variation",
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
            'id' => 'required|exists:variations,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {
            $id = $request->id;
            \DB::table('variations')->where('id', $id)->delete();
            \DB::table('variation_value')->where('variation_id', $id)->delete();
            \DB::commit();
            return $this->sendResponse(null, __('VARIATION_DELETED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

}

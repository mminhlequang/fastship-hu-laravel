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
     *     tags={"Variation"},
     *     summary="Create variation",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Variation object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="name_vi", type="string", example="Độ ngọt", description="Tên biến thể vi"),
     *          @OA\Property(property="name_en", type="string", example="Độ ngọt en", description="Tên biến thể en"),
     *          @OA\Property(property="name_hu", type="string", example="Độ ngọt hu", description="Tên biến thể hu"),
     *          @OA\Property(property="name_zh", type="string", example="Độ ngọt zh", description="Tên biến thể zh"),
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
                'name_vi' => 'required|max:120',
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
            if(!empty($request->values)){
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
            }

            \DB::commit();
            return $this->sendResponse(new VariationResource($data), __('TOPPING_GROUP_CREATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/variation/update",
     *     tags={"Variation"},
     *     summary="Update variation",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Variation object that needs to be update",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID option(biến thể)"),
     *          @OA\Property(property="name_vi", type="string", example="Độ ngọt", description="Tên biến thể vi"),
     *          @OA\Property(property="name_en", type="string", example="Độ ngọt en", description="Tên biến thể en"),
     *          @OA\Property(property="name_hu", type="string", example="Độ ngọt hu", description="Tên biến thể hu"),
     *          @OA\Property(property="name_zh", type="string", example="Độ ngọt zh", description="Tên biến thể zh"),
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
                'name_vi' => 'required|max:120'
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        \DB::beginTransaction();
        try {
            $id = $request->id;
            $data = Variation::find($id);

            $data->update($requestData);

            $data->refresh();

            // Lấy mảng topping_ids từ chuỗi
            if(!empty($request->values)){
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

                    if(!$exists){
                        \DB::table('variation_values')->insert([
                            'variation_id' => $data->id,
                            'value' => $itemV->value,
                            'price' => $itemV->price,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            \DB::commit();
            return $this->sendResponse(new VariationResource($data), __('TOPPING_GROUP_UPDATED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }



}

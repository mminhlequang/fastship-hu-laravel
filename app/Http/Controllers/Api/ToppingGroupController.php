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
     *     path="/api/v1/group",
     *     tags={"Group Topping"},
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
     *     @OA\Response(response="200", description="Get all group topping")
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        $storeId = $request->store_id ?? 0;

        try {
            $data = ToppingGroup::with('creator')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            })->where('store_id', $storeId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(ToppingGroupResource::collection($data), 'Get all topping successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/group/create",
     *     tags={"Group Topping"},
     *     summary="Create group topping",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Group topping object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="name_vi", type="string", example="Name vi", description="Tên VN"),
     *          @OA\Property(property="name_en", type="string", example="Name en", description="Tên EN"),
     *          @OA\Property(property="name_zh", type="string", example="Name zh", description="Tên ZH"),
     *          @OA\Property(property="name_hu", type="string", example="name hu", description="Tên HU"),
     *          @OA\Property(property="topping_ids", type="string", example="1,2,3", description="Danh sách topping liên kết"),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create group topping Successful"),
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
                'name_vi' => 'required|max:120',
                'name_en' => 'required|max:120',
                'name_zh' => 'required|max:120',
                'name_hu' => 'required|max:120',
                'store_id' => 'required|exists:stores,id',
                'topping_ids' => 'nullable|regex:/^(\d+)(,\d+)*$/', // Kiểm tra rằng topping_ids là chuỗi các số nguyên cách nhau bằng dấu phẩy.
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        \DB::beginTransaction();
        try {

            $requestData['creator_id'] = $customer->id;

            $data = ToppingGroup::create($requestData);

            // Lấy mảng topping_ids từ chuỗi
            if(!empty($request->topping_ids)){
                $toppingIds = explode(',', $request->topping_ids);
                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($toppingIds as $toppingId) {
                    \DB::table('toppings_group_link')->insert([
                        'topping_id' => $toppingId,
                        'group_id' => $data->id,
                    ]);
                }
            }
            \DB::commit();
            return $this->sendResponse(new ToppingGroupResource($data), __('api.groups_created'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/group/update",
     *     tags={"Group Topping"},
     *     summary="Update group topping",
     *     @OA\RequestBody(
     *         required=true,
     *         description="topping object that needs to be update",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID thể loại"),
     *          @OA\Property(property="name_vi", type="string", example="Name vi", description="Tên VN"),
     *          @OA\Property(property="name_en", type="string", example="Name en", description="Tên EN"),
     *          @OA\Property(property="name_zh", type="string", example="Name zh", description="Tên ZH"),
     *          @OA\Property(property="name_hu", type="string", example="name hu", description="Tên HU"),
     *          @OA\Property(property="topping_ids", type="string", example="1,2,3", description="Danh sách topping liên kết"),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update group topping Successful"),
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
                'id' => 'required|exists:topping,id',
                'name_vi' => 'required|max:120',
                'name_en' => 'required|max:120',
                'name_zh' => 'required|max:120',
                'name_hu' => 'required|max:120',
                'store_id' => 'required|exists:stores,id',
                'topping_ids' => 'nullable|regex:/^(\d+)(,\d+)*$/', // Kiểm tra rằng topping_ids là chuỗi các số nguyên cách nhau bằng dấu phẩy.
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        \DB::beginTransaction();
        try {

            $data = ToppingGroup::find($requestData['id']);

            $data->update($requestData);

            $data->refresh();

            // Lấy mảng topping_ids từ chuỗi
            if(!empty($request->topping_ids)){
                $toppingIds = explode(',', $request->topping_ids);
                // Duyệt qua từng topping_id và lưu vào bảng toppings_groups
                foreach ($toppingIds as $toppingId) {
                    // Kiểm tra xem cặp topping_id và group_id đã tồn tại chưa
                    $exists = \DB::table('toppings_group_link')
                        ->where('topping_id', $toppingId)
                        ->where('group_id', $data->id)
                        ->exists(); // Trả về true nếu đã tồn tại, false nếu chưa có

                    // Nếu chưa tồn tại, tiến hành insert
                    if (!$exists) {
                        \DB::table('toppings_group_link')->insert([
                            'topping_id' => $toppingId,
                            'group_id' => $data->id,
                        ]);
                    }
                }
            }
            \DB::commit();
            return $this->sendResponse(new ToppingResource($data), __('api.groups_updated'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/group/delete",
     *     tags={"Group Topping"},
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
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        try {
            \DB::table('toppings_group')->where('id', $request->id)->update([
                'deleted_at' => now()
            ]);
            return $this->sendResponse(null, __('api.groups_deleted'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


}

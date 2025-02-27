<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ToppingResource;
use App\Models\Customer;
use App\Models\Topping;
use Illuminate\Http\Request;
use Validator;

class ToppingController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/topping",
     *     tags={"Topping"},
     *     summary="Get all topping",
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
     *     @OA\Response(response="200", description="Get all topping")
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        $storeId = $request->store_id ?? 0;

        try {
            $data = Topping::with('creator')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            })->where('store_id', $storeId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(ToppingResource::collection($data), 'Get all topping successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/topping/create",
     *     tags={"Topping"},
     *     summary="Create topping",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Topping object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="name_vi", type="string", example="Name vi", description="Tên VN"),
     *          @OA\Property(property="name_en", type="string", example="Name en", description="Tên EN"),
     *          @OA\Property(property="name_zh", type="string", example="Name zh", description="Tên ZH"),
     *          @OA\Property(property="name_hu", type="string", example="name hu", description="Tên HU"),
     *          @OA\Property(property="image", type="string", example="abcd", description="Đường dẫn đến hình ảnh của sản phẩm hoặc mã giảm giá."),
     *          @OA\Property(property="price", type="double", example="1000", description="Giá tiền"),
     *          @OA\Property(property="status", type="integer", example="1", description="1:Còn món, 0:Hết món"),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create topping Successful"),
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
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'price' => 'required',
                'status' => 'required|in:0,1',
                'store_id' => 'required|exists:stores,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Topping::uploadAndResize($request->file('image'));

            $requestData['creator_id'] = $customer->id;

            $data = Topping::create($requestData);

            return $this->sendResponse(new ToppingResource($data), __('api.toppings_created'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/topping/update",
     *     tags={"Topping"},
     *     summary="Update topping",
     *     @OA\RequestBody(
     *         required=true,
     *         description="topping object that needs to be update",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID thể loại"),
     *          @OA\Property(property="name_vi", type="string", example="Name vi", description="Tên VN"),
     *          @OA\Property(property="name_en", type="string", example="Name en", description="Tên EN"),
     *          @OA\Property(property="name_zh", type="string", example="Name zh", description="Tên ZH"),
     *          @OA\Property(property="name_hu", type="string", example="name hu", description="Tên HU"),
     *          @OA\Property(property="image", type="string", example="abcd", description="Đường dẫn đến hình ảnh của sản phẩm hoặc mã giảm giá."),
     *          @OA\Property(property="price", type="double", example="1000", description="Giá tiền"),
     *          @OA\Property(property="status", type="integer", example="1", description="1:Còn món, 0:Hết món"),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update topping Successful"),
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
                'price' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'status' => 'required|in:0,1',
                'store_id' => 'required|exists:stores,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Topping::uploadAndResize($request->file('image'));

            $data = Topping::find($requestData['id']);

            $data->update($requestData);

            $data->refresh();

            return $this->sendResponse(new ToppingResource($data), __('api_topping_updated'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }

    /**
     * @OA\Post(
     *     path="/api/v1/topping/delete",
     *     tags={"Topping"},
     *     summary="Delete topping",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete topping",
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
            'id' => 'required|exists:topping,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        try {
            $toppingId = $request->id;

            \DB::table('toppings')->where('id', $toppingId)->update([
                'deleted_at' => now()
            ]);

            //Delete link group topping
            \DB::table('toppings_group_link')->where('topping_id', $toppingId)->delete();

            return $this->sendResponse(null, __('api.toppings_deleted'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


}

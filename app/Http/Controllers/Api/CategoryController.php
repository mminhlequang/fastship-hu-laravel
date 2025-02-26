<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\CategoryResource;
use App\Http\Resources\NewsResource;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     tags={"Category"},
     *     summary="Get all categories",
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
     *     @OA\Response(response="200", description="Get all categories")
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        try {
            $data = Category::with('parent')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            })->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(NewsResource::collection($data), 'Get all categories successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/categories/by_store",
     *     tags={"Category"},
     *     summary="Get all categories by store",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="store_id",
     *         required=true,
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
     *     @OA\Response(response="200", description="Get all categories by store")
     * )
     */
    public function getListByStore(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        $storeId = $request->store_id ?? 0;

        try {
            $data = Category::with('parent')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            })->where('store_id', $storeId)->whereNull('deleted_at')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(NewsResource::collection($data), 'Get all categories successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/categories/create",
     *     tags={"Category"},
     *     summary="Create categories",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Categories object that needs to be created",
     *         @OA\JsonContent(
     *          @OA\Property(property="name_vi", type="string", example="Name vi", description="Tên VN"),
     *          @OA\Property(property="name_en", type="string", example="Name en", description="Tên EN"),
     *          @OA\Property(property="name_zh", type="string", example="Name zh", description="Tên ZH"),
     *          @OA\Property(property="name_hu", type="string", example="name hu", description="Tên HU"),
     *          @OA\Property(property="image", type="string", example="abcd", description="Đường dẫn đến hình ảnh của sản phẩm hoặc mã giảm giá."),
     *          @OA\Property(property="description", type="string", example="abcd", description="Mô tả chi tiết về thể loại"),
     *          @OA\Property(property="arrange", type="integer", example="1", description="Sắp xếp thẻ loại"),
     *          @OA\Property(property="parent_id", type="date", example="1", description="ID thể loại cha. nếu ko có thì để null"),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Create categories Successful"),
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
                'description' => 'required|max:3000',
                'store_id' => 'required|exists:stores,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Category::uploadAndResize($request->file('image'));

            $requestData['user_id'] = $customer->id;

            $data = Category::create($requestData);

            return $this->sendResponse(new CategoryResource($data), __('api.categories_created'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/categories/update",
     *     tags={"Category"},
     *     summary="Update categories",
     *     @OA\RequestBody(
     *         required=true,
     *         description="categories object that needs to be update",
     *         @OA\JsonContent(
     *          @OA\Property(property="id", type="integer", example="1", description="ID thể loại"),
     *          @OA\Property(property="name_vi", type="string", example="Name vi", description="Tên VN"),
     *          @OA\Property(property="name_en", type="string", example="Name en", description="Tên EN"),
     *          @OA\Property(property="name_zh", type="string", example="Name zh", description="Tên ZH"),
     *          @OA\Property(property="name_hu", type="string", example="name hu", description="Tên HU"),
     *          @OA\Property(property="image", type="string", example="abcd", description="Đường dẫn đến hình ảnh của sản phẩm hoặc mã giảm giá."),
     *          @OA\Property(property="description", type="string", example="abcd", description="Mô tả chi tiết về thể loại"),
     *          @OA\Property(property="arrange", type="integer", example="1", description="Sắp xếp thẻ loại"),
     *          @OA\Property(property="parent_id", type="date", example="1", description="ID thể loại cha. nếu ko có thì để null"),
     *          @OA\Property(property="store_id", type="integer", example="1", description="ID của cửa hàng."),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Update categories Successful"),
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
                'id' => 'required|exists:categories,id',
                'name_vi' => 'required|max:120',
                'name_en' => 'required|max:120',
                'name_zh' => 'required|max:120',
                'name_hu' => 'required|max:120',
                'description' => 'required|max:3000',
                'store_id' => 'required|exists:stores,id',
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            if ($request->hasFile('image'))
                $requestData['image'] = Category::uploadAndResize($request->file('image'));

            $data = Category::find($requestData['id']);

            $data->update($requestData);

            $data->refresh();

            return $this->sendResponse(new CategoryResource($data), __('api_categories_updated'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }
    
    /**
     * @OA\Post(
     *     path="/api/v1/categories/delete",
     *     tags={"Category"},
     *     summary="Delete categories",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete categories",
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
            'id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        try {
            \DB::table('categories')->where('id', $request->id)->update([
                'deleted_at' => now()
            ]);
            return $this->sendResponse(null, __('api.categories_deleted'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }


}

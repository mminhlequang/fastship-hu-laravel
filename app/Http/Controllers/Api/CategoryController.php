<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryStoreResource;
use App\Http\Resources\NewsResource;
use App\Models\Category;
use App\Models\CategoryStore;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class CategoryController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/categories/get_categories",
     *     tags={"Category"},
     *     summary="Get all categories",
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="Id của store",
     *         required=false,
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
     *     @OA\Response(response="200", description="Get all categories"),
     * )
     */
    public function getCategories(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        $storeId = $request->store_id ?? 0;
        try {
            $data = Category::with('parent')->when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            })->when($storeId != 0, function ($query) use ($storeId) {
                $ids = \DB::table('categories_stores')->where('store_id', $storeId)->pluck('category_id')->toArray();
                $query->whereIn('id', $ids);
            })->whereNull('deleted_at')->orderBy(\DB::raw("SUBSTRING_INDEX(name_vi, ' ', -1)"), 'asc')->skip($offset)->take($limit)->get();

            return $this->sendResponse(CategoryResource::collection($data), 'Get all categories successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/categories/create",
     *     tags={"Category"},
     *     summary="Create categories",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Create categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="category_id", type="integer", example="1", description="Id category"),
     *             @OA\Property(property="store_id", type="integer", example="1", description="Id store"),
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

        $validator = Validator::make(
            $request->all(),
            [
                'store_id' => 'required|exists:stores,id',
                'category_id' => [
                    'required',
                    'exists:categories,id',
                    Rule::unique('categories_stores')->where(function ($query) use ($request) {
                        $storeId = $request->store_id;
                        return $query->where('store_id', $storeId);
                    })
                ],
            ],[
                'category_id.unique' => __('CATEGORY_EXITS')
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {

            $requestData['user_id'] = $customer->id;

            $data = CategoryStore::create($requestData);

            return $this->sendResponse(new CategoryResource($data->category), __('errors.CATEGORY_CREATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
     *             @OA\Property(property="category_id", type="integer", example="1", description="Id category"),
     *             @OA\Property(property="store_id", type="integer", example="1", description="Id store"),
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
            'category_id' => 'required|exists:categories,id',
            'store_id' => 'required|exists:stores,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            \DB::table('categories_stores')
                ->where('category_id', $request->category_id)
                ->where('store_id', $request->store_id)
                ->delete();
            return $this->sendResponse(null, __('CATEGORY_DELETED'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

}

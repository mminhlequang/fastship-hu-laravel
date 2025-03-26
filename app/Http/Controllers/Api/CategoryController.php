<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\CategoryStore;
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
                $query->whereHas('stores', function ($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                });
                $query->join('categories_stores', 'categories.id', '=', 'categories_stores.category_id')
                    ->select('categories.*') // Select all fields from the categories table
                    ->orderBy('categories_stores.arrange');
            })->with(['products' => function ($query) use ($storeId) {
                $query->whereHas('categories.stores', function ($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                });
                // Sắp xếp sản phẩm theo trường 'arrange' trong bảng trung gian
                $query->orderBy('categories_products.arrange', 'asc');  // Sắp xếp theo 'arrange
            }])
                ->whereNull('parent_id')->whereNull('deleted_at')->orderBy('name_vi', 'asc')->skip($offset)->take($limit)->get();

            return $this->sendResponse(CategoryResource::collection($data), 'Get all categories successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
     *             @OA\Property(property="product_ids", type="array", @OA\Items(type="integer"), example={1,2,3}, description="Danh sách sản phẩm"),
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

        $validator = Validator::make(
            $request->all(),
            [
                'store_id' => 'required|exists:stores,id',
                'product_ids' => 'nullable|array',
                'product_ids.*' => [
                    'nullable',
                    'integer', // Mỗi phần tử trong mảng phải là số nguyên
                    'exists:products,id', // Kiểm tra xem ID có tồn tại trong bảng products hay không
                ],
                'category_id' => [
                    'required',
                    'exists:categories,id',
                    Rule::unique('categories_stores')->where(function ($query) use ($request) {
                        $storeId = $request->store_id;
                        return $query->where('store_id', $storeId);
                    })
                ],
            ], [
                'category_id.unique' => __('CATEGORY_EXITS')
            ]
        );
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {

            $requestData['user_id'] = auth('api')->id();

            $data = CategoryStore::create($requestData);

            $category = $data->category; // lấy danh mục theo $categoryId
            $productIds = $request->product_ids; // mảng ID sản phẩm bạn muốn thêm

            // Attach các sản phẩm vào danh mục
            if (count($productIds) > 0) $category->products()->attach($productIds);

            return $this->sendResponse(new CategoryResource($category), __('errors.CATEGORY_CREATED'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/detail",
     *     tags={"Category"},
     *     summary="Get detail category by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         description="ID of the store",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     * )
     */
    public function detail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            // Lấy dữ liệu thể loại theo id
            $data = Category::with(['products' => function ($query) use ($request) {
                // Lọc các sản phẩm theo store_id
                $query->whereHas('categories.stores', function ($query) use ($request) {
                    $query->where('store_id', $request->store_id);
                });
            }])->find($requestData['id']);

            return $this->sendResponse(new CategoryResource($data), __("GET_DETAIL_SUCCESS"));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
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
        \DB::beginTransaction();
        try {
            //Xoá liên kết store
            \DB::table('categories_stores')
                ->where('category_id', $request->category_id)
                ->where('store_id', $request->store_id)
                ->delete();

            //Xoá liên kết products
            \DB::table('categories_products')
                ->where('category_id', $request->category_id)
                ->delete();

            \DB::commit();
            return $this->sendResponse(null, __('CATEGORY_DELETED'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }




}

<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\NewsResource;
use App\Models\Category;
use Illuminate\Http\Request;

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
            })->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(NewsResource::collection($data), 'Get all categories successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


}

<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Validator;

class NewsController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/news",
     *     tags={"News"},
     *     summary="Get all news",
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
     *     @OA\Response(response="200", description="Get all news")
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        $locale = app()->getLocale();
        try {
            $data = News::with('category')->when($keywords != '', function ($query) use ($keywords, $locale) {
                $query->where('name_' . $locale, 'like', "%$keywords%");
            })->latest()->skip($offset)->take($limit)->get();
            return $this->sendResponse(NewsResource::collection($data), 'Get all news successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }



    /**
     * @OA\Get(
     *     path="/api/news/detail",
     *     tags={"News"},
     *     summary="Get detail news by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID of the news",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="News details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News not found"
     *     )
     * )
     */
    public function detail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id' => 'required|exists:news,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $data = News::find($requestData['id']);
            return $this->sendResponse(new NewsResource($data), "Get detail successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


}

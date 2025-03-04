<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Validator;

class BannerController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/banners",
     *     tags={"Banner"},
     *     summary="Get all banners",
     *     @OA\Parameter(
     *         name="position",
     *         in="query",
     *         description="position",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Get all banners")
     * )
     */
    public function getListsBanner(Request $request)
    {
        try {
            $position = $request->position ?? '';
            $data = Banner::when($position != '', function ($query) use ($position) {
                $query->where('position', $position);
            })->where('active', 1)->orderBy('arrange')->get();
            return $this->sendResponse(BannerResource::collection($data), 'Get all banner successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

}

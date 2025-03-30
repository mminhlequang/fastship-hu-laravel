<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;


/**
 * @OA\SecurityScheme(
 *     securityScheme="apiKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-Api-Key"
 * )
 */

class BannerController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/banners",
     *     tags={"Banner"},
     *     summary="Get all banners",
     *     @OA\Parameter(
     *         name="country_code",
     *         in="query",
     *         description="country_code",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Get all banners"),
     * )
     */
    public function getListsBanner(Request $request)
    {
        try {
            $countryCode = $request->country_code ?? '';
            $data = Banner::when(!empty($countryCode), function ($query) use ($countryCode) {
                $query->where('country_code', $countryCode);
            })->where('active', 1)->orderBy('arrange')->get();
            return $this->sendResponse(BannerResource::collection($data), __('GET_BANNER_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

}

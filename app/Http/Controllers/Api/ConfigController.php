<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\AppConfigResource;
use App\Http\Resources\SupportChanelResource;
use App\Models\Setting;
use App\Models\SupportChanel;
use Illuminate\Http\Request;

class ConfigController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/config",
     *     tags={"Config"},
     *     summary="Get all coinfig",
     *     @OA\Response(response="200", description="Get all config"),
     * )
     */
    public function getConfig(Request $request)
    {
        try {
            $data = Setting::allConfigs();
            return $this->sendResponse(new AppConfigResource($data), __('GET_CONFIG_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/support_channels",
     *     tags={"Config"},
     *     summary="Get all support chanels",
     *     @OA\Parameter(
     *         name="is_for_driver",
     *         in="query",
     *         description="is_for_driver",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_for_partner",
     *         in="query",
     *         description="is_for_partner",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_for_customer",
     *         in="query",
     *         description="is_for_customer",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get support chanels"),
     * )
     */
    public function getSupportChannel(Request $request)
    {
        $is_for_driver = $request->is_for_driver ?? '';
        $is_for_partner = $request->is_for_partner ?? '';
        $is_for_customer = $request->is_for_customer ?? '';
        try {

            $data = SupportChanel::when($is_for_driver != '', function ($query) use ($is_for_driver) {
                $query->where('is_for_driver', $is_for_driver);
            })->when($is_for_partner != '', function ($query) use ($is_for_partner) {
                $query->where('is_for_partner', $is_for_partner);
            })->when($is_for_customer != '', function ($query) use ($is_for_customer) {
                $query->where('is_for_customer', $is_for_customer);
            })->orderBy('arrange')->get();

            return $this->sendResponse(SupportChanelResource::collection($data), __('GET_CONFIG_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }

}

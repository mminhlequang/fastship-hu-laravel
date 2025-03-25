<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\AppConfigResource;
use App\Models\Setting;
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

}

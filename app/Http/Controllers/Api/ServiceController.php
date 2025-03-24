<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceSupportResource;
use App\Models\Service;
use App\Models\SupportBusiness;
use App\Models\SupportService;
use App\Models\SupportServiceAdditional;
use Illuminate\Http\Request;

class ServiceController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/get_services_store",
     *     tags={"Service"},
     *     summary="Get all services",
     *     @OA\Parameter(
     *         name="Accept-Language",
     *         in="header",
     *         description="Language preference",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="vi"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         example="1",
     *         description="Type(1:Support Service, 2:Support Service Additional, 3:Business Type)",
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
     *     @OA\Response(response="200", description="Get all services"),
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $type = $request->type ?? 1;

        try {
            if ($type == 1)
                $data = SupportService::orderBy('name');
            else if ($type == 2)
                $data = SupportServiceAdditional::orderBy('name');
            else
                $data = SupportBusiness::orderBy('name');

            $data = $data->skip($offset)->take($limit)->get();

            $resourceData = ($type == 1) ? ServiceSupportResource::collection($data) : ServiceResource::collection($data);
            return $this->sendResponse($resourceData, __('GET_SERVICES_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


}

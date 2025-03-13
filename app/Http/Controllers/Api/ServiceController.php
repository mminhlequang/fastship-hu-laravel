<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/services",
     *     tags={"Service"},
     *     summary="Get all services",
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         example="1",
     *         description="Type(1:Type Service, 2:Service, 3:Food, 4:Product)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="keywords",
     *         in="query",
     *         description="Từ khoá tìm kiếm",
     *         required=false,
     *         @OA\Schema(type="string")
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
        $keywords = $request->keywords ?? "";
        $type = $request->type ?? 1;

        try {
            $data = Service::when($keywords != '', function ($query) use ($keywords) {
                $query->where('name_vi', 'like', "%$keywords%");
            })->where('type', $type)->whereNull('parent_id')->orderBy('arrange')->skip($offset)->take($limit)->get();

            return $this->sendResponse(ServiceResource::collection($data), __('GET_SERVICES_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }


}

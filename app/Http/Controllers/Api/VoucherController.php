<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\VoucherResource;
use App\Models\Discount;
use Illuminate\Http\Request;

class VoucherController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/vouchers",
     *     tags={"Voucher"},
     *     summary="Get all vouchers",
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
     *     @OA\Response(response="200", description="Get all vouchers")
     * )
     */
    public function getLists(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $keywords = $request->keywords ?? "";
        try {
            $data = Discount::when($keywords != '', function ($query) use ($keywords) {
                $query->where('code', 'like', "%$keywords%");
            })->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(VoucherResource::collection($data), 'Get all vouchers successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }
    }


}

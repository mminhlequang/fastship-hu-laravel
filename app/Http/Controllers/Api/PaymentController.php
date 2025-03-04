<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/payment_method",
     *     tags={"Payment Method"},
     *     summary="Get all payment method",
     *     @OA\Response(response="200", description="Get all banners")
     * )
     */
    public function getList(Request $request)
    {
        try {

            $data = PaymentMethod::where('active', 1)->orderBy('arrange')->get();
            return $this->sendResponse(PaymentMethodResource::collection($data), 'Get all banner successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }
    }

}

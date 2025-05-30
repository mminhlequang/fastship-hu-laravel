<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        // Tính application_fee, 3% của subtotal
        $application_fee = $this->total_price * 0.03;
        $total = $this->total_price + $this->price_tip + $this->fee + $application_fee - $this->voucher_value;

        return [
            'id' => $this->id,
            'code' => $this->code,
            'currency' => $this->currency,
            'delivery_type' => $this->delivery_type,
            'payment_status' => $this->payment_status,
            'process_status' => $this->process_status,
            'note' => $this->note,
            'cancel_note' => $this->cancel_note,
            'payment' => ($this->payment != null) ? new PaymentWalletResource($this->payment) : null,
            'store' => ($this->store != null) ? new StoreBaseResource($this->store) : null,
            'customer' => ($this->customer != null) ? new CustomerShortResource($this->customer) : null,
            'driver' => ($this->driver != null) ? new CustomerShortResource($this->driver) : null,
            'items' => OrderItemResource::collection($this->orderItems),
            "phone" => $this->phone,
            "street" => $this->street,
            "zip" => $this->zip,
            "city" => $this->city,
            "state" => $this->state,
            "country" => $this->country,
            "country_code" => $this->country_code,
            "lat" => $this->lat,
            "lng" => $this->lng,
            "address" => $this->address,
            'ship_fee' => $this->fee,
            'tip' => $this->price_tip,
            "discount" => $this->voucher_value ?? 0,
            'application_fee' => (float)$application_fee,
            "subtotal" => $this->total_price,
            "total" => (float)$total,
            "ship_distance" => $this->ship_distance,
            "ship_estimate_time" => $this->ship_estimate_time,
            "ship_polyline" => $this->ship_polyline,
            "ship_here_raw" => $this->ship_here_raw,
            "store_status" => $this->store_status,
            "voucher" => ($this->voucher != null) ? new VoucherShortResource($this->voucher) : null,
            "time_order" => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            "time_pickup_estimate" => null,
            "time_pickup" => null,
            "time_delivery" => null,
            "rating" => [
                "store" => ($this->storeRating != null) ? new OrderStoreRatingResource($this->storeRating) : null,
                "items" => OrderProductRatingResource::collection($this->productRating)
            ]
        ];
    }
}

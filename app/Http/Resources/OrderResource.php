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
        return [
            'id' => $this->id,
            'code' => $this->code,
            'total_price' => $this->total_price,
            'currency' => $this->currency,
            'payment_type' => $this->payment_type,
            'payment_status' => $this->payment_status,
            'process_status' => $this->process_status,
            'note' => $this->note,
            'payment' => ($this->payment != null) ? new PaymentWalletResource($this->payment) : null,
            'store' => ($this->store != null) ? new StoreBaseResource($this->store) : null,
            'customer' => ($this->customer != null) ? new CustomerShortResource($this->customer) : null,
            'driver' => ($this->driver != null) ? new CustomerShortResource($this->driver) : null,
            'items' => OrderItemResource::collection($this->orderItems),
            'fee' => $this->fee,
            'price_tip' => $this->price_tip,
            "distance" => 1,
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
            "voucher" => ($this->voucher != null) ? new VoucherShortResource($this->voucher) : null,
            "voucher_value" => $this->voucher_value,
            "time_order" => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            "time_pickup_estimate" => null,
            "time_pickup" => null,
            "time_delivery" => null
        ];
    }
}

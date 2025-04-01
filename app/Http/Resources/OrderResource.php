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
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'note' => $this->note,
            'payment' => ($this->payment != null) ? new PaymentWalletResource($this->payment) : null,
            'approve' => ($this->approve != null) ? new ApproveResource($this->approve) : null,
            'store' => ($this->store != null) ? new StoreBaseResource($this->store) : null,
            'customer' => ($this->customer != null) ? new CustomerResource($this->customer) : null,
            'driver' => ($this->driver != null) ? new CustomerResource($this->driver) : null,
            'items' => OrderItemResource::collection($this->orderItems),
            'fee' => $this->fee,
            'voucher_value' => $this->voucher_value,
            'distance' => 1,
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
            'time_order' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            'time_pickup_estimate' => Carbon::parse($this->created_at)->addMinutes(10)->format('d/m/Y H:i'),
            'time_pickup' => Carbon::parse($this->created_at)->addMinutes(10)->format('d/m/Y H:i'),
            'time_delivery' => Carbon::parse($this->created_at)->addMinutes(10)->format('d/m/Y H:i'),
        ];
    }
}

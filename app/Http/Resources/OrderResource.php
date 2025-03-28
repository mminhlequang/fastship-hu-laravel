<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
            'approve' => ($this->approve != null) ? new ApproveResource($this->approve) : null,
            'store' => ($this->store != null) ? new StoreResource($this->store) : null,
            'customer' => ($this->customer != null) ? new CustomerResource($this->customer) : null,
            'driver' => ($this->driver != null) ? new CustomerResource($this->driver) : null,
            'items' => OrderItemResource::collection($this->orderItems),
            'fee' => 0,
            'voucher_value' => 0,
            'distance' => 1,
            'time_order' => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
            'time_pickup_estimate' => Carbon::parse($this->created_at)->addMinutes(10)->format('d/m/Y H:i'),
            'time_pickup' => Carbon::parse($this->created_at)->addMinutes(10)->format('d/m/Y H:i'),
            'time_delivery' => Carbon::parse($this->created_at)->addMinutes(10)->format('d/m/Y H:i'),
        ];
    }
}

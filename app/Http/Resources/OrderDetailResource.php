<?php

namespace App\Http\Resources;

use App\BookingItem;
use App\Models\AddressDelivery;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $total_price = json_decode($this->amount)->total_price;
        $bookingItems = BookingItem::where('booking_id', $this->id)->get();
        $address = AddressDelivery::where('id', $this->address_id)->first();
        return [
            'id' => $this->id,
            'code' => $this->code,
            'address' => new DeliveryAddressResource($address),
            'total_price' => intval($total_price),
            'approve' => optional($this->approve)->name,
            'payment' => $this->payment_type,
            'approve_id' => $this->approve_id,
            'orders' => OrderItemResource::collection($bookingItems),
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d/m/Y'),
        ];
    }
}

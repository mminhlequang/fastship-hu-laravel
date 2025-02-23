<?php

namespace App\Http\Resources;

use App\BookingItem;
use App\Models\AddressDelivery;
use App\Models\Discount;
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
        $bookingItems = BookingItem::where('booking_id', $this->id)->get();
        $amount = json_decode($this->amount,true);
        return [
            'id' => $this->id,
            'status' => $this->approve->id,
            'price' => number_format($amount['total_price']),
            'products' => OrderItemResource::collection($bookingItems),
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d/m/Y'),
        ];
    }
}

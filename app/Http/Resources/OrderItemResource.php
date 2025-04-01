<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'price' => $this->price,
            'quantity' => $this->quantity,
            'product' => $this->product ? new CartProductResource((object)$this->product) : null, // Convert to object if it's an array
            'variations' => $this->variations ? CartVariationResource::collection(collect($this->variations)->map(function ($item) {
                return (object) $item; // Converts each variation array item to object
            })) : [], // Handle variations
            'toppings' => $this->toppings ? CartToppingResource::collection(collect($this->toppings)->map(function ($item) {
                return (object) $item; // Converts each topping array item to object
            })) : [], // Handle toppings
        ];
    }
}

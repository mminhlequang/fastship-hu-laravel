<?php

namespace App\Http\Resources;

use App\Models\TypeDiscount;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'cart_value' => $this->cart_value,
            'image' => $this->image ? asset($this->image) : '',
            'description' => html_entity_decode($this->description) ?? '' ,
            'value' => $this->value,
            'sale_maximum' => $this->sale_maximum,
            'expired_date' => \Carbon\Carbon::parse($this->expiry_date)->format('d/m/Y'),
            'type' => $this->type
        ];
    }
}

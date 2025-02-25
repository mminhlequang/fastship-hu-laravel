<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->image ? url($this->image) : '',
            'cart_value' => $this->cart_value,
            'sale_maximum' => $this->sale_maximum,
            'description' => $this->description,
            'value' => $this->value,
            'expiry_date' => $this->expiry_date,
            'type ' => $this->type,
            'active' => $this->active,
            'created_at' => $this->created_at,
        ];
    }
}

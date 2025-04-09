<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'image' => 'assets/icons/cart/pr2.png',
            'cart_value' => $this->cart_value,
            'sale_maximum' => $this->sale_maximum,
            'description' => $this->description,
            'content' => $this->content,
            'value' => $this->value,
            'currency' => 'eur',
            'product_ids' => $this->product_ids,
            'start_date' => Carbon::parse($this->start_date)->format('d/m/Y'),
            'expiry_date' => Carbon::parse($this->expiry_date)->format('d/m/Y'),
            'type' => $this->type,
            'active' => $this->active,
            'is_valid' => $this->is_valid ?? 0,
            "created_at" => $this->created_at
        ];
    }
}

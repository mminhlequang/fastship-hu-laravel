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
            'image' => $this->image,
            'cart_value' => $this->cart_value,
            'sale_maximum' => $this->sale_maximum,
            'description' => $this->description,
            'value' => $this->value,
            'product_ids' => $this->product_ids,
            'start_date' => Carbon::parse($this->start_date)->format('d/m/Y'),
            'expiry_date' => Carbon::parse($this->expiry_date)->format('d/m/Y'),
            'type ' => $this->type,
            'active' => $this->active,
            "created_at" => Carbon::parse($this->created_at)->format('d/m/Y H:i')
        ];
    }
}

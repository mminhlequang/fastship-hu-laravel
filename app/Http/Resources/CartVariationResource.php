<?php

namespace App\Http\Resources;

use App\Helper\LocalizationHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CartVariationResource extends JsonResource
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
            'variation' => isset($this->variation) ? new CartVariationValueResource((object)$this->variation) : null,  // Name according to the current locale,  // Name according to the current locale
            'value' => $this->value,
            'price' => $this->price,
        ];
    }
}

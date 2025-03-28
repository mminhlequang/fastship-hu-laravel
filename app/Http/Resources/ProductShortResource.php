<?php

namespace App\Http\Resources;


use App\Helper\LocalizationHelper;
use App\Models\ToppingGroup;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'price' => $this->price,
            'price_compare' => $this->price + 5,
        ];
    }
}
<?php

namespace App\Http\Resources;


use App\Helper\LocalizationHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource
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
            'name' => LocalizationHelper::getNameByLocale($this),
            'image' => $this->image,
            'price' => $this->price,
            'price_compare' => $this->price + 5,
            'content' => $content ?? '',
            'quantity' => 1,
            'time_open' => $this->time_open,
            'time_close' => $this->time_close,
            'created_at' => $this->created_at
        ];
    }
}
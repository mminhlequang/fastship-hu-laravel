<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertiesResource extends JsonResource
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
            'size_id' => $this->size_id,
            'pack_id' => $this->pack_id,
            'material_id' => $this->material_id,
            'color_id' => $this->color_id,
            'weight_id' => $this->weight_id,
            'price' => $this->price,
            'price_compare' => $this->price_compare,
            'price_won' => $this->price_won,
            'price_compare_won' => $this->price_compare_won
        ];
    }
}

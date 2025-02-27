<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ToppingGroupResource extends JsonResource
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
            'name' => $this->getNameByLocale(),
            'image' => $this->image ? url($this->image) : '',
            'status' => $this->status,
            'toppings' => ToppingResource::collection($this->toppings)
        ];
    }
}

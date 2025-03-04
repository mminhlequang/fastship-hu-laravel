<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' =>  $this->getNameByLocale(),
            'image' => $this->image,
            'price' => $this->price,
            'content' => $content  ?? '',
            'quantity' => 1,
            'active' => $this->active,
            "rating" => $this->averageRating(),
            "toppings" => ($this->group != null) ? ToppingResource::collection($this->group->toppings) : [],
            'created_at' => $this->created_at,
        ];
    }
}
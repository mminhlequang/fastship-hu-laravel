<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'created_at' => $this->created_at,
        ];
    }
}
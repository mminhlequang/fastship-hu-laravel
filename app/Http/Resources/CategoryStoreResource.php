<?php

namespace App\Http\Resources;

use App\CategoryProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryStoreResource extends JsonResource
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
            'children' => ProductResource::collection($this->products),
        ];
    }
}

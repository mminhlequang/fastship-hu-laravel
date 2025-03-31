<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreShortResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "address" => $this->address,
            "avatar_image" => $this->avatar_image,
            "facade_image" => $this->facade_image,
            "rating" => $this->averageRating(),
            "categories" => CategoryShortResource::collection($this->categories)
        ];
    }
}

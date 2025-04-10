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
        $isFavorite = \DB::table('stores_favorite')->where('user_id', auth('api')->id())->where('store_id', $this->id)->exists() ? 1 : 0;

        return [
            "id" => $this->id,
            "name" => $this->name,
            "address" => $this->address,
            "avatar_image" => $this->avatar_image,
            "facade_image" => $this->facade_image,
            "rating" => $this->averageRating(),
            "is_favorite" => $isFavorite,
            "categories" => CategoryShortResource::collection($this->categories)

        ];
    }
}

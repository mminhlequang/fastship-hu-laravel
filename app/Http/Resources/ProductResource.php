<?php

namespace App\Http\Resources;


use App\Helper\LocalizationHelper;
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
        $isFavorite = \DB::table('products_favorite')->where('user_id', auth('api')->id())->where('product_id', $this->id)->exists() ? 1 : 0;
        return [
            'id' => $this->id,
            'name' => LocalizationHelper::getNameByLocale($this),
            'image' => $this->image,
            'price' => $this->price,
            'content' => $content  ?? '',
            'quantity' => 1,
            'active' => $this->active,
            'rating' => $this->averageRating(),
            'toppings' => ($this->group != null) ? ToppingResource::collection($this->group->toppings) : [],
            'variations' => VariationResource::collection($this->variations),
            "is_favorite" => $isFavorite,
            'created_at' => $this->created_at
        ];
    }
}
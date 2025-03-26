<?php

namespace App\Http\Resources;


use App\Helper\LocalizationHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $isFavorite = \DB::table('products_favorite')->where('user_id', auth('api')->id())->where('product_id', $this->id)->exists() ? 1 : 0;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'price' => $this->price,
            'price_compare' => $this->price + 5,
            'description' => $this->description ?? '',
            'content' => $this->content ?? '',
            'quantity' => 1,
            'rating' => $this->averageRating(),
            'variations' => VariationResource::collection($this->variations),
            'toppings' => ToppingResource::collection($this->toppings),
            "is_favorite" => $isFavorite,
            "store" => ($this->store != null) ? new StoreResource($this->store) : null,
            'status' => $this->status,
            "is_open" => $this->isStoreOpen(),
            "operating_hours" => StoreHourResource::collection($this->hours),

            'created_at' => $this->created_at
        ];
    }
}
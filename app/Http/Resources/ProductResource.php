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
        // Lấy thời gian hiện tại
        $now = now();
        $status = 0; // Mặc định là không có sẵn
        if ($this->status == 1 || ($this->time_open <= $now && $this->time_close >= $now)) {
            $status = 1; // Món ăn có sẵn
        }


        return [
            'id' => $this->id,
            'name' => LocalizationHelper::getNameByLocale($this),
            'image' => $this->image,
            'price' => $this->price,
            'price_compare' => $this->price + 5,
            'content' => $content ?? '',
            'quantity' => 1,
            'rating' => $this->averageRating(),
            'variations' => VariationResource::collection($this->variations),
            'toppings' => ToppingResource::collection($this->toppings),
            "is_favorite" => $isFavorite,
            "category" => ($this->category != null) ? new CategoryResource($this->category) : null,
            "store" => ($this->store != null) ? new StoreResource($this->store) : null,
            'status' => $status,
            'created_at' => $this->created_at
        ];
    }
}
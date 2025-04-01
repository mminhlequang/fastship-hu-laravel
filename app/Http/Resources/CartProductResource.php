<?php

namespace App\Http\Resources;


use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $product = Product::find($this->id);
        $variations = $product->variationsX;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'price' => $this->price,
            'price_compare' => $this->price + 5,
            'description' => $this->description,
            'variations' => VariationResource::collection($variations)
        ];
    }
}
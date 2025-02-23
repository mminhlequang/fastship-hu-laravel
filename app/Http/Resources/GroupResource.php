<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $products = Product::with('product_properties')->whereIn('id', explode(',', $this->product_id))->skip($request->offset ?? 0)->take($request->limit ?? 10)->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'products' => ProductResource::collection($products)
        ];
    }
}

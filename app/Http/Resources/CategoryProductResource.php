<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $products = Product::with('product_properties')->where([['brand_id', $request->brand_id], ['category_id', $this->id]])->orderBy('created_at', 'DESC');
        switch ($request->search_type) {
            case 1:
                $products = $products->orderBy('sales', 'DESC')->get();
                break;
            case 2:
                $products = $products->get();
                break;
            case 3:
                $products = $products->get();
                break;
            case 4:
                $products = $products->get();
                break;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'products' => ProductResource::collection($products)
        ];
    }
}

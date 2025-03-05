<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'id' => $this['id'],
            'product' => $this['product'],
            'variations' => $this['variations'], // Lấy thông tin biến thể đã chọn
            'toppings' => $this['toppings'], // Lấy thông tin topping đã chọn
            'quantity' => $this['quantity'], // Số lượng sản phẩm
            'price' => $this['price'], // Tổng giá trị của item
        ];
    }
}

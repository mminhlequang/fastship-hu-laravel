<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreMenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {

        $children = ($request->type == 1) ? ProductResource::collection($this->products) : ToppingResource::collection($this->toppings);

        return [
            'id' => $this->id,
            'name' => $this->name_vi,
            'children' => $children
        ];
    }
}

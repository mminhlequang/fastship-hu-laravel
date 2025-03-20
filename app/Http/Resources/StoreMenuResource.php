<?php

namespace App\Http\Resources;

use App\Helper\LocalizationHelper;
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
        $type = $request->type ?? 1;
        $children = ($type == 1) ? ProductResource::collection($this->products) : ToppingResource::collection($this->toppings);

        return [
            'id' => $this->id,
            'name' => LocalizationHelper::getNameByLocale($this),
            'items' => $children
        ];
    }
}

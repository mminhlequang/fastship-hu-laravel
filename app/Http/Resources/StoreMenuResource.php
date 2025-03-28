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
        $children = ($type == 1) ? StoreMenuProductResource::collection($this->products) : ToppingResource::collection($this->toppings);

        $name = ($type == 1) ? LocalizationHelper::getNameByLocale($this) : $this->name;
        return [
            'id' => $this->id,
            'name' => $name,
            'items' => $children
        ];
    }
}

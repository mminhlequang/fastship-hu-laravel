<?php

namespace App\Http\Resources;

use App\Helper\LocalizationHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryShortResource extends JsonResource
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
            'id' => $this->id,
            'name' => LocalizationHelper::getNameByLocale($this),
            'image' => $this->image,
            'description' => LocalizationHelper::getNameByLocale($this, 'description')
        ];
    }
}

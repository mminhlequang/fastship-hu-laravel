<?php

namespace App\Http\Resources;

use App\Helper\LocalizationHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => LocalizationHelper::getNameByLocale($this),
            "image" => $this->image,
            "description" => $this->description,
            "content" => $this->content,
            "country_code" => $this->country_code,
            "category" => 'Blog',
            "external_link" => url('news/' . $this->slug . '.html'),
            "created_at" => $this->created_at
        ];
    }
}

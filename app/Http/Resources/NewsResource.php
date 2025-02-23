<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "image" => asset(\Storage::url($this->image)),
            "category_id" => $this->category_id,
            "description" => $this->description,
        //    "content" => $this->content,
            "updated_at" => $this->updated_at->toDateTimeString(),
	        "url" => url("api/v1/news/" . $this->id)
        ];
    }
}

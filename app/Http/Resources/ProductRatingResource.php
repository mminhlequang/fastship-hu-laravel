<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductRatingResource extends JsonResource
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
            'user' => ($this->user != null) ? new CustomerResource($this->user) : null,
            'content' => $this->content,
            'star' => $this->star,
            'order_code' => optional($this->order)->code,
            'images' => FileResource::collection($this->images->where('type', 1)),
            'videos' => FileResource::collection($this->images->where('type', 2)),
            'replies' => ReplyResource::collection($this->replies),
            "created_at" => $this->created_at
        ];
    }
}

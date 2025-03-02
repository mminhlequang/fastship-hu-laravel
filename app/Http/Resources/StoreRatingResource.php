<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreRatingResource extends JsonResource
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
            'images' => ImageResource::collection($this->images),
            'replies' => ReplyResource::collection($this->replies)
        ];
    }
}

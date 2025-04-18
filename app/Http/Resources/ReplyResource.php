<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
            'id' => $this->id,
            'content' => $this->content  ?? '',
            'user' => ($this->creator != null) ? new CustomerResource($this->creator) : null,
            'created_at' => $this->created_at
        ];
    }
}
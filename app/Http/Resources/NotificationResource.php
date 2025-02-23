<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title' => $this->title,
			'content' =>$this->content,
            'image' => $this->image ? asset($this->image) : '',
			'type' => $this->type,
			'read' => $this->read,
			'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d-m-Y')
        ];
    }
}

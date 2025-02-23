<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class LogActivityResource extends Resource
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
        	'description' => $this->description,
	        'user' => optional($this->user)->name,
	        'created_at' => $this->created_at->format(config('settings.format.datetime'))
        ];
    }
}

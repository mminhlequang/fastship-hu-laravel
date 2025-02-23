<?php

namespace App\Http\Resources;

use App\Models\Target;
use Illuminate\Http\Resources\Json\JsonResource;

class TargetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $children = Target::all();
        return [
            'id' => $this->category->id,
            'name' => $this->category->name,
            'slug' => $this->category->slug,
            'image' =>  asset($this->category->image)  ?? "",
        ];
    }
}

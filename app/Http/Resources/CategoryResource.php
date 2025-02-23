<?php

namespace App\Http\Resources;

use App\CategoryProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $children = CategoryProduct::with('parent')->where('parent_id', $this->id)->where('active',1)->get();
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'icon' => $this->icon ?? '',
            'image' => $this->image ? asset($this->image) : '',
            // 'description' => $this->description ?? '',
            // 'children' => CategoryResource::collection($children),
            'active' => $this->active
        ];
    }
}

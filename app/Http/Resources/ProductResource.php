<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' =>  $this->getNameByLocale(),
            'image' => $this->image ? url($this->image) : '',
            'price' => $this->price,
            'content' => $content  ?? '',
            'quantity' => $this->sales,
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d/m/Y'),
        ];
    }
}
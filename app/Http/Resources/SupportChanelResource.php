<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportChanelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'url' => $this->url,
            'phone_number' => $this->phone_number,
            'icon' => $this->icon,
            'is_for_driver' => $this->is_for_driver,
            'is_for_partner' => $this->is_for_partner,
            'is_for_customer ' => $this->is_for_customer ,
        ];
    }
}

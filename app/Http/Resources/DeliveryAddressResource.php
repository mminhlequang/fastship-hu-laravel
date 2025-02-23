<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryAddressResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'province' => optional($this->provinces)->name,
            'district' => optional($this->districts)->name,
            'ward' => optional($this->wards)->name,
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,
            'ward_id' => $this->ward_id,
            'is_default' => $this->is_default,
        ];
    }
}

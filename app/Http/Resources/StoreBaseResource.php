<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreBaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            "id" => $this->id,
            "name" => $this->name,
            "address" => $this->address,
            "phone" => $this->phone,
            "avatar_image" => $this->avatar_image,
            "facade_image" => $this->facade_image,
            "street" => $this->street,
            "zip" => $this->zip,
            "city" => $this->city,
            "state" => $this->state,
            "country" => $this->country,
            "country_code" => $this->country_code,
            "lat" => $this->lat,
            "lng" => $this->lng,
        ];
    }
}

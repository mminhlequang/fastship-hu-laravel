<?php

namespace App\Http\Resources;

use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            "banner" => $this->banner,
            "image" => $this->image,
            "phone" => $this->phone,
            "description" => $this->description,
            "content" => $this->content,
            "street" => $this->street,
            "zip" => $this->zip,
            "city" => $this->city,
            "state" => $this->state,
            "country" => $this->country,
            "country_code" => $this->country_code,
            "lat" => $this->lat,
            "lng" => $this->lng,
            "address" => $this->address,
            "operating_hours" => $this->operating_hours,
            "is_open" => Store::isStoreOpen($this->id),
            "created_at" => Carbon::parse($this->created_at)->format('d/m/Y H:i')
        ];
    }
}

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
        $isFavorite = \DB::table('stores_favorite')->where('user_id', auth('api')->id())->where('store_id', $this->id)->exists() ? 1 : 0;

        return [
            "id" => $this->id,
            "name" => $this->name,
            "banner" => $this->banner,
            "image" => $this->image,
            "type" => $this->type,
            "phone" => $this->phone,
            "phone_other" => $this->phone_other,
            "phone_contact" => $this->phone_contact,
            "email" => $this->email,
            "license" => $this->license,
            "cccd" => $this->cccd,
            "cccd_date" => $this->cccd_date,
            "image_cccd_before" => $this->image_cccd_before,
            "image_cccd_after" => $this->image_cccd_after,
            "image_license" => $this->image_license,
            "image_tax_code" => $this->image_tax_code,
            "tax_code" => $this->tax_code,
            "fee" => $this->fee,
            "rating" => $this->averageRating(),
            "is_open" => $this->isStoreOpen(),
            "active" => $this->active,
            "operating_hours" => StoreHourResource::collection($this->hours),
            "services" => $this->services,
            "foods" => $this->foods,
            "products" => $this->products,
            "images" => ImageResource::collection($this->images),

            "address" => $this->address,
            "street" => $this->street,
            "zip" => $this->zip,
            "city" => $this->city,
            "state" => $this->state,
            "country" => $this->country,
            "country_code" => $this->country_code,
            "lat" => $this->lat,
            "lng" => $this->lng,
            'is_favorite' => $isFavorite,
            "created_at" => $this->created_at
        ];
    }
}

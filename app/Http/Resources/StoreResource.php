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
            "phone" => $this->phone,
            "contact_type" => $this->contact_type,
            "contact_full_name" => $this->contact_full_name,
            "contact_company" => $this->contact_company,
            "contact_company_address" => $this->contact_company_address,
            "contact_phone" => $this->contact_phone,
            "contact_email" => $this->contact_email,
            "contact_card_id" => $this->contact_card_id,
            "contact_card_id_issue_date" => $this->contact_card_id_issue_date,
            "contact_card_id_image_front" => $this->contact_card_id_image_front,
            "contact_card_id_image_back" => $this->contact_card_id_image_back,
            "contact_tax" => $this->contact_tax,
            "avatar_image" => $this->avatar_image,
            "facade_image" => $this->facade_image,

            "rating" => $this->averageRating(),
            "is_open" => $this->isStoreOpen(),
            "operating_hours" => StoreHourResource::collection($this->hours),
            "banner_images" => ImageResource::collection($this->images),
            "contact_documents" => ImageResource::collection($this->documents),

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
            "active" => $this->active,
            "categories" => StoreCategoryResource::collection($this->categories),
            "distance" => Store::getDistance($request->lat, $request->lng, $this->lat, $this->lng),
            "products" => ProductShortResource::collection($this->products),
            "created_at" => $this->created_at
        ];
    }
}

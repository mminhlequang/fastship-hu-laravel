<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDetailResource extends JsonResource
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
            'uid' => $this->uid,
            'name' => $this->name ?? '',
            'avatar' => $this->getAvatarDefault(),
            'phone' => $this->phone ?? '',
            'email' => $this->email ?? '',
            'address' => $this->address ?? '',
            'birthday' => $this->birthday,
            "street" => $this->street,
            "zip" => $this->zip,
            "city" => $this->city,
            "state" => $this->state,
            "country" => $this->country,
            "country_code" => $this->country_code,
            "code_introduce" => $this->code_introduce,
            "cccd" => $this->cccd,
            "image_cccd_before" => $this->image_cccd_before,
            "image_cccd_after" => $this->image_cccd_after,
            "sex" => $this->sex ?? 1,
            "lat" => $this->lat,
            "lng" => $this->lng,
            "rating" => $this->averageRating(),
            "money" => $this->getBalance(),
            "active" => $this->active,
            "enabled_notify" => $this->enabled_notify,
            "car" => ($this->car != null) ? new DataResource($this->car) : null,
            "deleted_at" => ($this->deleted_request_at != NULL) ? Carbon::parse($this->deleted_request_at)->format('d/m/Y H:i') : null,
            "deleted_request_at" => ($this->deleted_request_at != NULL) ? Carbon::parse($this->deleted_request_at)->format('d/m/Y H:i') : null,
            "images" => ImageResource::collection($this->images),
            "profile" => ($this->type == 2 && $this->profile != null) ? new ProfileResource($this->profile) : null,
            "code" => $this->code,
            "created_at" => $this->created_at,
        ];
    }
}

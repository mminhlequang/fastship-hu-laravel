<?php

namespace App\Http\Resources;

use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name ?? '',
            'avatar' => $this->avatar ? url($this->avatar) : url('images/avatar.png'),
            'phone' => $this->phone ?? '',
            'email' => $this->email ?? '',
            'address' => $this->address ?? '',
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
            "sex" => $this->sex,
            "lat" => $this->lat,
            "lng" => $this->lng,
            "rating" => $this->averageRating(),
            "money" => $this->getBalance(),
            "active" => $this->active,
            "type" => $this->type,
            "is_confirm" => $this->is_confirm,
            "deleted_at" => ($this->deleted_at != NULL) ? Carbon::parse($this->deleted_request_at)->format('d/m/Y H:i') : null,
            "deleted_request_at" => ($this->deleted_request_at != NULL) ? Carbon::parse($this->deleted_request_at)->format('d/m/Y H:i') : null,
            "created_at" => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
        ];
    }
}

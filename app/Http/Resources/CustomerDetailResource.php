<?php

namespace App\Http\Resources;

use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDetailResource extends JsonResource
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
            'avatar' => $this->avatar ? asset($this->avatar) : asset(config('settings.avatar_default')),
            'phone' => $this->phone ?? '',
            'email' => $this->email ?? '',
            'address' => $this->address ?? '',
            "street" => $this->street,
            "zip" => $this->zip,
            "city" => $this->city,
            "state" => $this->state,
            "country" => $this->country,
            "country_code" => $this->country_code,
            "lat" => $this->lat,
            "lng" => $this->lng,
            "rating" => $this->averageRating(),
            "money" => $this->getBalance(),
            "images" => ImageResource::collection($this->images),
            "active" => $this->active,
            "deleted_at" => ($this->deleted_at != NULL) ? Carbon::parse($this->deleted_request_at)->format('d/m/Y H:i') : null,
            "deleted_request_at" => ($this->deleted_request_at != NULL) ? Carbon::parse($this->deleted_request_at)->format('d/m/Y H:i') : null,
            "created_at" => Carbon::parse($this->created_at)->format('d/m/Y H:i'),
        ];
    }
}

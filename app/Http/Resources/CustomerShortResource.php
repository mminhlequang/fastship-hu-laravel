<?php

namespace App\Http\Resources;

use App\Models\Customer;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerShortResource extends JsonResource
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
            'uid' => $this->uid,
            'name' => $this->name,
            'avatar' => $this->avatar ?? Customer::getAvatarDefault($this->type),
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }
}

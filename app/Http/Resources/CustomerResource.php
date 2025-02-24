<?php

namespace App\Http\Resources;

use App\Models\Language;
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
            'avatar' => $this->avatar ? asset($this->avatar) : asset(config('settings.avatar_default')),
            'phone' => $this->phone ?? '',
            'email' => $this->email ?? '',
            'address' => $this->address ?? '',
        ];
    }
}

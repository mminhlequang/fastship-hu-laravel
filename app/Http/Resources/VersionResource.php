<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VersionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'android_version' => $this->android_version,
            'ios_version' => $this->ios_version,
            'android_number' => $this->android_number,
            'ios_number' => $this->ios_number,
            'requied_android_version' => $this->requied_android_version,
            'requied_ios_version' => $this->requied_ios_version,
            'android_store' => $this->android_store,
            'ios_store' => $this->ios_store
        ];
    }
}

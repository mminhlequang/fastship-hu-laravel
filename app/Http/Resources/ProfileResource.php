<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'sex' => $this->sex,
            'birthday' => $this->birthday,
            'code_introduce' => $this->code_introduce,
            'address' => $this->address,
            'cccd' => $this->cccd,
            'cccd_date' => $this->cccd_date,
            'image_cccd_before' => $this->image_cccd_before,
            'image_cccd_after' => $this->image_cccd_after,
            'address_temp' => $this->address_temp,
            'is_tax_code' => $this->is_tax_code,
            'tax_code' => $this->tax_code,
            'payment_method' => $this->payment_method,
            'card_number' => $this->card_number,
            'card_expires' => $this->card_expires,
            'card_cvv' => $this->card_cvv,
            'contacts' => $this->contacts,
            'car_id' => $this->car_id,
            'license' => $this->license,
            'image_license_before' => $this->image_license_before,
            'image_license_after' => $this->image_license_after
        ];
    }
}
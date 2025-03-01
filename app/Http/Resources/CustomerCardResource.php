<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCardResource extends JsonResource
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
            'card_holder_name' => $this->card_holder_name,
            'card_brand' => $this->card_brand,
            'gateway' => $this->gateway,
            'card_exp_month' => $this->card_exp_month,
            'card_exp_year' => $this->card_exp_year,
            'card_last4' => $this->card_last4,
            'set_as_default' => $this->set_as_default,
            'fingerprint' => $this->fingerprint
        ];
    }
}

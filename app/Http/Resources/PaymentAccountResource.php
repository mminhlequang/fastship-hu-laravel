<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentAccountResource extends JsonResource
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
            'account_type' => $this->account_type,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'bank_name' => $this->bank_name,
            'currency' => $this->currency,
            'is_verified' => $this->is_verified,
            'is_default' => $this->is_default,
            'payment_wallet_provider' => ($this->payment_wallet != null) ? new PaymentWalletResource($this->payment_wallet) : null,
            'created_at' => $this->created_at,
        ];
    }
}

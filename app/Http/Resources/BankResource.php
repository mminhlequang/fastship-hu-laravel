<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
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
            'account_holder' => $this->account_holder,
            'bank' => $this->bank,
            'account_number' => $this->account_number,
            'branch' => $this->branch,
            'status' => $this->status,
            'nation' => $this->nation ?? 0,
        ];
    }

}
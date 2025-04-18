<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'code' => $this->code,
            'price' => $this->price,
            'currency' => $this->currency,
            'order' => ($this->order != null) ? new OrderShortResource($this->order) : null,
            'description' => $this->description,
            'payment_method' => $this->payment_method,
            'type' => $this->type,
            'transaction_type' => $this->transaction_type,
            'status' => $this->status,
            'paid_date' => ($this->transaction_date != null) ? Carbon::parse($this->transaction_date)->format('d/m/Y H:i'): null,
            "created_at" => $this->created_at
        ];
    }
}

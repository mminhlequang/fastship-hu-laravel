<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionReportOrder extends JsonResource
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
            'items' => OrderItemResource::collection($this->orderItems),
            'created_at' => $this->created_at
        ];
    }
}

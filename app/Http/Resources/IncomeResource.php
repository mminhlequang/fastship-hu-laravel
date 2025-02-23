<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $amount = json_decode(optional($this->booking)->amount);
        return [
            'id' => $this->id,
            'order_id' => optional($this->booking)->code,
            'order_value' => intval($amount->total_price),
            'income' => intval($this->income),
            'status' => $this->type ,
            'created_at' => \Carbon\Carbon::parse($this->date)->format('d/m/Y')
        ];
    }
}

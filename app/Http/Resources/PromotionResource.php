<?php

namespace App\Http\Resources;

use App\Models\Discount;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$discounts = Discount::whereIn('id', explode(',', $this->discount_id))->get();
        return [
        	'id' => $this->id,
	        'name' => $this->name,
            'banner' => $this->banner ? asset($this->banner) : '',
			'description' => $this->description ?? '',
            'view' => $this->view,
			'date_start' => \Carbon\Carbon::parse($this->date_start)->format('d/m/Y'),
			'date_end' => \Carbon\Carbon::parse($this->date_end)->format('d/m/Y'),
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d/m/Y'),
			'discounts' => DiscountResource::collection($discounts),
        ];
    }
}

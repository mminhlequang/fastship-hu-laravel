<?php

namespace App\Http\Resources;

use App\Models\Customer;
use App\Models\GalleryReview;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $customer = Customer::find($this->customer_id);
        $files = GalleryReview::where('review_id', $this->id)->get();
        return [
            'id' => $this->id,
            'user' => new CustomerResource($customer),
            'files' => FileResource::collection($files),
            'rating' => $this->rating,
            'review' => $this->review ?? '',
            'created_at' => \Carbon\Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Customer;
use App\Models\GalleryReviewBrand;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Language;
class PostResource extends JsonResource
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
        $files = GalleryReviewBrand::where('post_id', $this->id)->get();
        $product = Product::find($this->product_id);
        $locale = app()->getLocale();

        if(isset($product->translations))
        {
            $data = json_decode($product->translations, true);
            $name12 = '';
            foreach ($data as $key => $value) {
                $langugesId = Language::where('prefix', $key)->pluck('prefix');
                if ($key == $locale) {
                    $name12 = $value['name_trans'];
                }
            }
        }
        return [
            'id' => $this->id,
            'user' => new CustomerResource($customer),
            'files' => FileResource::collection($files),
            'name' => $name12,
            'product_id' => $product->id,
            'rating' => $this->rating,
            'review' => $this->review ?? '',
            'like' => $this->like ?? 0,
            'sub_id' => $this->sub_id ?? '',
            'people_activity' => $this->people_activity ?? '',
            'created_at' => \Carbon\Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}

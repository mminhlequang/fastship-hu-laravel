<?php

namespace App\Http\Resources;

use App\Color;
use App\GalleryProduct;
use App\Models\Material;
use App\Models\Pack;
use App\Models\Review;
use App\Models\Size;
use App\Models\Weight;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Language;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $price = $this->product_properties[0]->price ?? 1;
        $price_compare = $this->product_properties[0]->price_compare ?? 1;

        $price_won = $this->product_properties[0]->price_won ?? 1;
        $price_compare_won = $this->product_properties[0]->price_compare_won ?? 1;
        $images = GalleryProduct::where('product_id', $this->id)->orderBy('id','DESC')->get();
        $review = Review::where('product_id', $this->id)->count();
        $sizeIds = array();
        $packIds = array();
        $materialIds = array();
        $colorIds = array();
        $weightIds = array();
        foreach ($this->product_properties as $item) {
            if (!in_array($item->size_id, $sizeIds)) {
                $sizeIds[] = $item->size_id;
            }
            if (!in_array($item->pack_id, $packIds)) {
                $packIds[] = $item->pack_id;
            }
            if (!in_array($item->material_id, $materialIds)) {
                $materialIds[] = $item->material_id;
            }
            if (!in_array($item->color_id, $colorIds)) {
                $colorIds[] = $item->color_id;
            }
            if (!in_array($item->weight_id, $weightIds)) {
                $weightIds[] = $item->weight_id;
            }
        }
        $locale = app()->getLocale();

        $data = json_decode($this->translations,true); 
     
            foreach($data as $key => $value){
      $langugesId = Language::where('prefix',$key)->pluck('prefix');
                    if($key == $locale){
                        $name1 = $value['name_trans'];
                        $content = $value['content_trans'];

                    }
        }
        $sizes = Size::whereIn('id', $sizeIds)->select('id', 'name')->orderBy('name', 'ASC')->get();
        $packs = Pack::whereIn('id', $packIds)->select('id', 'name')->orderBy('name', 'ASC')->get();
        $materials = Material::whereIn('id', $materialIds)->select('id', 'name')->orderBy('name', 'ASC')->get();
        $colors = Color::whereIn('id', $colorIds)->select('id', 'name')->orderBy('name', 'ASC')->get();
        $weights = Weight::whereIn('id', $weightIds)->select('id', 'name')->orderBy('name', 'ASC')->get();
        $properties = array(
            'sizes' => $sizes,
            'packs' => $packs,
            'materials' => $materials,
            'colors' => $colors,
            'weights' => $weights
        );

        return [
            'id' => $this->id,
            'name' =>  $name1,
            'image' => $this->image ? asset($this->image) : '',
            'images' => GalleryResource::collection($images),
            'price' => $price,
            'price_compare' => $price_compare,
            'price_won' => $price_won,
            'price_compare_won' => $price_compare_won,
            'content' => $content  ?? '',
            'isKOL' => $this->isKOL ?? 0 ,
            'star' => round(optional($this->reviews)->avg('rating'), 1) ?? 1,
            'review' => $review,
            'quantity' => $this->sales,
            'percent' => $this->percent,
            'sale_off' => $this->sale_off ?? 1,
            'available' => $this->amount_inventory > 0 ? 1 : 0,
            'properties' => $properties,
            'listProperties' => PropertiesResource::collection($this->product_properties),
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d/m/Y'),
        ];
    }
}
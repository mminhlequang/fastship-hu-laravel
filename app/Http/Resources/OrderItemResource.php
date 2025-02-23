<?php

namespace App\Http\Resources;

use App\Color;
use App\Models\Material;
use App\Models\Pack;
use App\Models\ProductProperty;
use App\Models\Size;
use App\Models\Weight;
use App\Models\Product;
use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $properties = ProductProperty::find($this->properties);
        $size = !empty($properties->size_id) ? Size::find($properties->size_id)->name : '';
        $pack = !empty($properties->pack_id) ? Pack::find($properties->pack_id)->name : '';
        $color = !empty($properties->color_id) ? Color::find($properties->color_id)->name : '';
        $weight = !empty($properties->weight_id) ? Weight::find($properties->weight_id)->name : '';
        $material = !empty($properties->material_id) ? Material::find($properties->material_id)->name : '';

        
        // $locale = app()->getLocale();

        // $productss = Product::where('id',$properties->product_id)->first();
        // if(isset($productss->translations))
        // {
        //     $data = json_decode($productss->translations, true);
        //     $name12 = '';
        //     foreach ($data as $key => $value) {
        //         $langugesId = Language::where('prefix', $key)->pluck('prefix');
        //         if ($key == $locale) {
        //             $name12 = $value['name_trans'];
        //         }
        //     }
        // }
        return [
            'id' => optional($this->product)->id,
            'name' => trim(optional($this->product)->name . ' ' . ($size ? 'Size ' . $size . ' ' : '') . ($pack ? 'Đóng gói ' . $pack . ' ' : '') . ($material ? 'Vật liệu ' . $material . ' ' : '') . ($color ? 'Màu ' . $color . ' ' : '') . ($weight ? 'Khối lượng ' . $weight . ' ' : '')),
            'image' => optional($this->product)->image ? asset(optional($this->product)->image) : '',
            'price' => $properties->price ?? 0 ,
            'quantity' => intval($this->quantity)
        ];
    }
}

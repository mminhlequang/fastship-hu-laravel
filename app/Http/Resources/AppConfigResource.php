<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppConfigResource extends JsonResource
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
            'company_name' => $this['company_info'][1]['value'] ?? '',
            'phone' => $this['company_info'][3]['value'] ?? '',
            'email' => $this['company_info'][5]['value'] ?? '',
            'hotline' => $this['company_info'][4]['value'] ?? '',
            'zalo' => $this['social_info'][5]['value'] ?? '',
            'messager' => $this['social_info'][7]['value'] ?? '',
        ];
    }
}

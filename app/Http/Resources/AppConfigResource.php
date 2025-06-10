<?php

namespace App\Http\Resources;

use App\Models\Config;
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
        $data = Config::first();

        return [
            'company' => $this['company_info'][1]['value'] ?? '',
            'phone' => $this['company_info'][3]['value'] ?? '',
            'email' => $this['company_info'][5]['value'] ?? '',
            'hotline' => $this['company_info'][4]['value'] ?? '',
            'zalo' => $this['social_info'][5]['value'] ?? '',
            'messager' => $this['social_info'][7]['value'] ?? '',
            'privacy' => $this['system_policy'][2]['value'] ?? '',
            'about' => $data->about,
            'application_fee' => 3
        ];
    }
}

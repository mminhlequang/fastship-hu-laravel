<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $language = Language::find($this->language_id);
        return [
            'id' => $this->id,
            'language_id' => intval($this->language_id),
            'languageName' => $language->name ?? "",
            'enable_notification' => intval($this->enable_notification),
            'name' => $this->name ?? '',
            'code' => $this->code ?? '',
            'phone' => $this->phone ?? '',
            'avatar' => $this->avatar ? asset($this->avatar) : asset(config('settings.avatar_default')),
            'email' => $this->email ?? '',
            'money' => intval($this->money) ?? 0,
            'agent_id' => $this->agent_id,
            'reference_code' => $this->referral_code ?? "",
        ];
    }
}

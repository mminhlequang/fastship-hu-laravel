<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Session\Store;

class ProfileResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
	    return [
		    'phone' => $this->phone,
		    'address' => $this->address,
		    'birthday' => $this->birthday,
		    'gender' => $this->gender,
		    'gender_text' => $this->textGender,
		    'position' => $this->position,
		    'avatar' => \Storage::exists($this->avatar)?asset(\Storage::url($this->avatar)):null
	    ];
    }
}

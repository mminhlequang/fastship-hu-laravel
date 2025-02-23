<?php

namespace App\Http\Resources;

use App\Models\CompanySetting;
use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
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
		    'id' => $this->id,
		    'username' => $this->username,
		    'email' => $this->email,
		    'name' => $this->name,
		    'role' => $this->roles->pluck('name'),
		    'profile' => new ProfileResource($this->profile),
		    'company' => CompanySetting::profile(),//Lấy thông tin công ty
		    $this->mergeWhen($this->roleBelongToAgent(), [
			    'agent' => new AgentResource($this->agent)
		    ]),
	    ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StepUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "comment" => $this->comment,
            "status" => $this->status,
            "image" => $this->image,
            "link" => $this->link,
        ];
    }
}

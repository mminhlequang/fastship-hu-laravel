<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreHourResource extends JsonResource
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
            "day" => $this->day,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "is_off" => $this->is_off,
        ];
    }
}

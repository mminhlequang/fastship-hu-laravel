<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $customerId = auth('api')->id() ?? 0;
        $userIds = ($this->user_id != null) ? explode(',', $this->user_id) : [];
        $isRead = in_array($customerId, $userIds);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
            'type' => $this->type,
            'reference_id' => $this->order_id,
            'is_read' => $isRead,
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d-m-Y H:i')
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Customer;
use App\Models\CustomerStep;
use Illuminate\Http\Resources\Json\JsonResource;

class StepResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $customer = Customer::getAuthorizationUser($request);
        $customerId = $customer->id ?? 0;
        $reply = CustomerStep::where('user_id', $customerId)->first();


        $status = ($reply != null) ? $reply->status : 'unfinished';
        $isRequest = \DB::table('customers_steps')
            ->where('step_id', $this->id)
            ->where('user_id', $customerId)
            ->exists();
        $content = ($reply == null) ? $this->content : $this->content_pending;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $content,
            'status' => $status,
            'is_request' => $isRequest ? 1 : 0,
            'reply' => ($reply && $reply->comment != NULL) ? new StepUserResource($reply) : null
        ];
    }
}

<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;


class SendNotificationFcmEvent
{
    use SerializesModels;
    public $title;
    public $description;
    public $userId;
    public $payload;

    public function __construct($title, $description, $userId, $payload)
    {
        $this->title = $title;
        $this->description = $description;
        $this->userId = $userId;
        $this->payload = $payload;
    }

    public function broadcastOn()
    {
        return [];
    }


}

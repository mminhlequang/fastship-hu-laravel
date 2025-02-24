<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;


class SendNotificationEvent
{
    use SerializesModels;
    public $title;
    public $description;
    public $image;
    public $type;
    public $userId;
    public $notifyId;

    public function __construct($title, $description, $image, $type, $userId = 0, $notify_id = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->type = $type;
        $this->userId = $userId;
        $this->notifyId = $notify_id;
    }

    public function broadcastOn()
    {
        return [];
    }


}

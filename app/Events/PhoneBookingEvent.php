<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhoneBookingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $phone, $users;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
        $this->users = User::all()->pluck('id');
    }
}

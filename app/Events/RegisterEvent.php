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

class RegisterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $agentRegister, $admin;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($agentRegister)
    {
        $this->agentRegister = $agentRegister;
        $this->admin = User::whereHas('roles', function ($query){
            $query->where( 'name', '=', config('settings.roles.company_admin') );
        })->pluck('id');
    }
}

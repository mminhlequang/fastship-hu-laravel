<?php

namespace App\Listeners;

use App\Models\LogActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //
        try{
        	if(config('settings.log_active'))
	            LogActivity::create([
	                'description' => $event->user->name." has logged at ".date('d/m/Y H:i:s'),
	                'content_id' => $event->user->id,
	                'content_type' => get_class($event->user),
	                'content' => '',
	                'url' => \Request::fullUrl(),
	                'method' => \Request::method(),
	                'action' => 'login',
	                'ip' => \Request::ip(),
	                'agent' => \Request::header('user-agent'),
	                'user_id' => $event->user->id
	            ]);
        }catch(Exception $e){

        }
    }
}

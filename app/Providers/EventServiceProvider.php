<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
	    'Illuminate\Auth\Events\Login' => [
		    'App\Listeners\LogSuccessfulLogin',
	    ],
        'App\Events\LogEvent' => [
	        'App\Listeners\LogListenerAction',
        ],
        'App\Events\RegisterEvent' => [
            'App\Listeners\SendMailActiveAfterRegisterAgent',
        ],
        'App\Events\PhoneBookingEvent' => [
            'App\Listeners\PhoneBookingListenerNotification',
        ],
        'App\Events\MailContactEvent' => [
            'App\Listeners\SendMailContactListener',
        ],
        'App\Events\MailOrderEvent' => [
            'App\Listeners\SendMailOrderListener',
        ],
        'App\Events\SendNotificationFcmEvent' => [
            'App\Listeners\SendNotificationFcmListener',
        ],
        'App\Events\SendNotificationEvent' => [
            'App\Listeners\SendNotificationListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

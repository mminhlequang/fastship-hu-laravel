<?php

namespace App\Listeners;

use App\Events\LogEvent;
use App\Models\LogActivity;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogListenerAction implements ShouldQueue
{
	public $tries = 1;
	/**
	 * Create the event listener.
	 * BookingListenerNotification constructor.
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  LogEvent  $event
	 * @return void
	 */
	public function handle(LogEvent $event)
	{
		$model = $event->model;
		$action = $event->action;
		$userAction = $event->userAction;
		$request = $event->request;

		$reflect = new \ReflectionClass($model);
		if(config('settings.log_active')) {
			$description = ucfirst( $action ) . " a " . $reflect->getShortName();
			if($reflect->getShortName() === 'Booking'){
				$model->load(['customer','detail.bookingable']);
			}
			LogActivity::create( [
				'description'  => $description,
				'content_id'   => $model->id ?? $model->user_id,
				'content_type' => get_class( $model ),
				'content'      => json_encode( $model ),
				'action'       => $action,
				'url'          => $request['url'],
				'method'       => $request['method'],
				'ip'           => $request['ip'],
				'agent'        => $request['agent'],
				'user_id'      => !empty($userAction) ? $userAction->id : null
			] );
		}
	}
}

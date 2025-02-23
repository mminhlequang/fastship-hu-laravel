<?php

namespace App\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class LogEvent
{
	use SerializesModels;
	public $model, $userAction, $action, $request;

	/**
	 * LogEvent constructor.
	 *
	 * @param $action
	 * @param $model
	 * @param $request
	 */
	public function __construct($action, $model, Request $request)
	{
		$this->action = $action;
		$this->model = $model;
		$this->userAction = \Auth::check() ? auth()->user() : null;
		$this->request = [
			'url'          => $request->fullUrl(),
			'method'       => $request->method(),
			'ip'           => $request->ip(),
			'agent'        => $request->header( 'user-agent' ),
		];
	}
}

<?php

namespace App\Traits;

use App\Models\LogActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelEventLogger
 * @package App\Traits
 *
 *  Automatically Log save, Add, Update, Delete events of Model.
 */
trait ModelEventLogger {

    /**
     * Automatically boot with Model, and register Events handler.
     */
    protected static function bootModelEventLogger()
    {
        foreach (static::getRecordActivityEvents() as $eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                try {
                    $reflect = new \ReflectionClass($model);
	                if(config('settings.log_active')) {
		                $modelDiff = $model->getDirty();
		                $description = ucfirst( $eventName ) . " a " . $reflect->getShortName();
		                if($reflect->getShortName() === 'Booking'){
			                if($modelDiff->approved === config('settings.approved.cancel')){
				                $description = "Cancel a " . $reflect->getShortName();
			                }
			                $modelDiff = $modelDiff->concat($model->customer);
		                }
		                LogActivity::create( [
			                'description'  => $description,
			                'content_id'   => $model->id ?? $model->user_id,
			                'content_type' => get_class( $model ),
			                'content'      => json_encode( $modelDiff ),
			                'url'          => \Request::fullUrl(),
			                'method'       => \Request::method(),
			                'action'       => static::getActionName( $eventName ),
			                'ip'           => \Request::ip(),
			                'agent'        => \Request::header( 'user-agent' ),
			                'user_id'      => \Auth::user()->id
		                ] );
	                }
                } catch (\Exception $e) {
                    return true;
                }
            });
        }
    }

    /**
     * Set the default events to be recorded if the $recordEvents
     * property does not exist on the model.
     *
     * @return array
     */
    protected static function getRecordActivityEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created',
            'updated',
            'deleted',
        ];
    }

    /**
     * Return Suitable action name for Supplied Event
     *
     * @param $event
     * @return string
     */
    protected static function getActionName($event)
    {
        switch (strtolower($event)) {
            case 'created':
                return 'create';
                break;
            case 'updated':
                return 'update';
                break;
            case 'deleted':
                return 'delete';
                break;
            default:
                return 'unknown';
        }
    }
}
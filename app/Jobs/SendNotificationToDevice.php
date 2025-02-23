<?php

namespace App\Jobs;

use App\DeviceNotify;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Psr\Http\Message\ResponseInterface;
use Psy\Util\Json;

class SendNotificationToDevice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $tries = 2;

    protected $users, $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users, $data)
    {
        $this->users = $users;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    if(empty($this->users) || empty($this->data)) return;

	    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
	    $serverKey = env('FIREBASE_FCM_SERVER_KEY');
	    if(empty($serverKey)) return;

	    $this->users->load('deviceNotifies');
		//get tokens
	    $tokenMobiles = [];
	    $tokenWebs = [];
	    foreach ($this->users as $user){
		    foreach ($user->deviceNotifies as $token){
		    	if($token->device_type === DeviceNotify::$TYPE_WEB)
				    $tokenWebs[] = $token->token;
		    	else
			        $tokenMobiles[] = $token->token;
		    }
	    }
	    if(count($tokenMobiles) === 0 && count($tokenWebs) === 0) return;
//		\Log::info('Token Web', $tokenWebs);
//	    \Log::info('Token Mobile', $tokenMobiles);
	    $headers = [
		    'Authorization' => 'key='.$serverKey,
		    'Content-Type' => 'application/json'
	    ];

//	    //send mobile
	    if(count($tokenMobiles) > 0) {
		    //data notification
		    $fcmNotification = $this->data;

		    if ( count( $tokenMobiles ) > 1 ) {
			    $fcmNotification['registration_ids'] = $tokenMobiles; //multple token array
		    } else {
			    $fcmNotification['to'] = head( $tokenMobiles ); //single token
		    }
		    $client = new Client();
		    $client->request( 'POST', $fcmUrl, [
			    'headers' => $headers,
			    'body'    => Json::encode( $fcmNotification ),
			    'verify'  => env( 'CURL_VERIFY', true )
		    ] );//requestAsync
	    }
	    //send website
	    if(count($tokenWebs) > 0) {
		    //data notification
		    $fcmNotification = $this->data;

		    if ( count( $tokenWebs ) > 1 ) {
			    $fcmNotification['registration_ids'] = $tokenWebs; //multple token array
		    } else {
			    $fcmNotification['to'] = head( $tokenWebs ); //single token
		    }
		    $fcmNotification['notification']['click_action'] = $fcmNotification['data']['url'];
		    $client = new Client();
		    $client->request( 'POST', $fcmUrl, [
			    'headers' => $headers,
			    'body'    => Json::encode( $fcmNotification ),
			    'verify'  => env( 'CURL_VERIFY', true )
		    ] );//requestAsync
	    }
    }
}

<?php

namespace App\Listeners;

use App\Events\SendNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;


class SendNotificationListener implements ShouldQueue
{
    protected $projectId;
    protected $serviceAccount;

    public function __construct()
    {
        // Đường dẫn đến tệp JSON của tài khoản dịch vụ
        $this->serviceAccount = json_decode(file_get_contents(public_path('fastshiphu-1ac6c-firebase-adminsdk-fbsvc-6827938595.json')), true);
        $this->projectId = $this->serviceAccount['project_id'];
    }

    public function handle(SendNotificationEvent $event)
    {
        if ($event->userId == 0) {
            // send to topic
            $this->sendTopicNotification($event);
        } else {
            // Retrieve all device tokens for the user
            $firebaseTokens = \DB::table('customers')
                ->where('id', $event->userId)
                ->pluck('device_token')
                ->toArray(); // Convert to array

            // Check if there are tokens to send notifications
            if (empty($firebaseTokens)) {
                return response()->json(['message' => 'No device tokens found'], 404);
            }

            // Prepare the FCM message payload
            $this->sendNotification($firebaseTokens, $event);
        }
    }

    protected function sendNotification(array $firebaseTokens, SendNotificationEvent $event)
    {
        // Lấy token OAuth2
        $accessToken = $this->getAccessToken();
        // Loop through each token and send a notification
        foreach ($firebaseTokens as $firebaseToken) {
            $data = [
                "message" => [
                    "token" => $firebaseToken,
                    "notification" => [
                        "title" => $event->title ?? "",
                        "body" => $event->description ?? "",
                        "image" => !empty($event->image) ? asset($event->image) : url('images/icon_gift.png')
                    ],
                    "data" => [
                        'id' => (string)$event->notifyId ?? "", // Ensure these are strings
                        'type_notification' => (string)$event->type, // Ensure these are strings
                        "type" => "notification",
                        "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    ],
                    'android' => [
                        'notification' => [
                            'sound' => 'default', // Use 'default' for standard notification sound
                        ]
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'mutable-content' => 1
                            ]
                        ],
                    ],
                ]
            ];

            $dataString = json_encode($data);

            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/check-7a85e/messages:send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            curl_close($ch);
        }
    }

    protected function sendTopicNotification(SendNotificationEvent $event)
    {
        try {
            // Lấy token OAuth2
            $accessToken = $this->getAccessToken();

            $data = [
                "message" => [
                    "topic" => "check_now",
                    "notification" => [
                        "title" => $event->title ?? "",
                        "body" => $event->description ?? ""
                    ],
                    'android' => [
                        'notification' => [
                            'sound' => 'default', // Use 'default' for standard notification sound
                        ]
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'mutable-content' => 1
                            ]
                        ],
                    ],
                ]
            ];

            $dataString = json_encode($data);

            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/check-7a85e/messages:send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            \Log::info("Send success: " . $httpCode);
        } catch (\Exception $e) {
            \Log::info("Send error: " . $e);
        }
    }

    protected function getAccessToken()
    {
        $key = $this->serviceAccount['private_key'];
        $clientEmail = $this->serviceAccount['client_email'];
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        // Tạo JWT Token
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $claims = json_encode([
            'iss' => $clientEmail,
            'scope' => implode(' ', $scopes),
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => time(),
            'exp' => time() + 3600,
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlClaims = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($claims));
        $signature = '';
        openssl_sign($base64UrlHeader . '.' . $base64UrlClaims, $signature, $key, OPENSSL_ALGO_SHA256);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $base64UrlHeader . '.' . $base64UrlClaims . '.' . $base64UrlSignature;

        // Gửi yêu cầu để lấy access token
        $client = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ],
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }
}

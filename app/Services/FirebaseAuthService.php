<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\RevokedIdToken;
use Kreait\Firebase\Exception\AuthException;

class FirebaseAuthService
{
    protected $auth;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(public_path('fastshiphu-1ac6c-firebase-adminsdk-fbsvc-6827938595.json'));
        $this->auth = $factory->createAuth();
    }

    // Verify Firebase ID Token
    public function verifyIdToken(string $idToken)
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            return $verifiedIdToken;
        } catch (AuthException $e) {
            throw new \Exception("Error verify token user: " . $e->getMessage());
        }
    }

    // Register user with phone and password, returning token and refresh_token
    public function registerWithPhoneAndPassword(string $phoneNumber, string $fullName)
    {
        try {
            // Firebase does not allow direct password registration for phone numbers.
            $user = $this->auth->createUser([
                'uid' => Str::uuid(),
                'phoneNumber' => Customer::convertPhoneNumber($phoneNumber),
                'displayName' => $fullName,  // Save the display name
                // You can set other parameters like email if you prefer, but this step registers only by phone number
            ]);

            $signInResult = $this->auth->signInAsUser($user);

            return [
                'access_token' => $signInResult->idToken(),
                'refresh_token' => $signInResult->refreshToken(),
                'expires_in' => $signInResult->data()['expiresIn']
            ];

        } catch (AuthException $e) {
            throw new \Exception("Error registering user: " . $e->getMessage());
        }
    }

    // Login and return both access token and refresh token
    public function login(string $phone)
    {
        try {
            $user = $this->auth->getUserByPhoneNumber($phone);
            // Here, you should implement your logic to authenticate the user based on email and password
            // This is just an example, you might use Firebase's custom authentication tokens or another method
            $signInResult = $this->auth->signInAsUser($user);
            // Normally Firebase refresh tokens are managed by client-side SDKs
            return [
                'access_token' => $signInResult->idToken(),
                'refresh_token' => $signInResult->refreshToken(),
                'expires_in' => $signInResult->data()['expiresIn']
            ];

        } catch (AuthException $e) {
            throw new \Exception("Error authenticating user: " . $e->getMessage());
        }
    }


    // Get user data by access token
    public function getUserByAccessToken(string $accessToken)
    {
        try {
            // Verify the ID token (access token)
            $verifiedIdToken = $this->auth->verifyIdToken($accessToken);

            // Get user UID from the verified token
            $uid = $verifiedIdToken->claims()->get('sub');
            // Get user details from Firebase
            $user = $this->auth->getUser($uid);
            return [
                'uid' => $user->uid,
                'email' => $user->email,
                'phone_number' => $user->phoneNumber,
                'display_name' => $user->displayName,
                'photo_url' => $user->photoUrl,
                'metadata' => $user->metadata,
                'custom_claims' => $user->customClaims,
            ];
        } catch (RevokedIdToken $e) {
            throw new \Exception("The token has been revoked: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception("Error retrieving user: " . $e->getMessage());
        }
    }

    // Refresh token by verifying expired token and generating new one
    public function refreshToken(string $refreshToken)
    {
        try {
            // Làm mới token
            $response = $this->auth->signInWithRefreshToken($refreshToken);
            $newIdToken = $response->idToken();
            $newRefreshToken = $response->refreshToken();

            // Lấy UID từ ID Token mới
            $verifiedIdToken = $this->auth->verifyIdToken($newIdToken);
            $uid = $verifiedIdToken->claims()->get('sub');

            // Thu hồi toàn bộ session của user (xóa tất cả token)
            $this->auth->revokeRefreshTokens($uid);

            // Yêu cầu token mới
            return [
                'access_token' => $newIdToken,
                'refresh_token' => $newRefreshToken,
            ];

        } catch (RevokedIdToken $e) {
            throw new \Exception("The token has been revoked: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception("Error refreshing token: " . $e->getMessage());
        }
    }
}

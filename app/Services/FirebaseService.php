<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseService
{
    protected $auth;

    public function __construct()
    {
        $this->auth = (new Factory)
            ->withServiceAccount(config('services.firebase.credentials'))
            ->createAuth();
    }

    // Verify Firebase ID Token
    public function verifyIdToken(string $idToken)
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            return $verifiedIdToken;
        } catch (FailedToVerifyToken $e) {
            return null;
        }
    }

    // Register a new user
    public function createUser(array $userData)
    {
        try {
            return $this->auth->createUser($userData);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Sign in with Firebase ID Token
    public function signInWithIdToken(string $idToken)
    {
        $verifiedToken = $this->verifyIdToken($idToken);

        if ($verifiedToken) {
            // The Firebase ID Token is valid, now you can use the user information
            return $this->auth->getUser($verifiedToken->getClaim('sub'));
        }

        return null;
    }
}

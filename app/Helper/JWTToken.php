<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    public static function createToken($userEmail, $userId)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24 * 7,
            'userEmail' => $userEmail,
            'userId' => $userId,
            'type' => 'auth',
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function createTokenResetPWD($userEmail)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            //time for 5 minutes
            'exp' => time() + 60 * 5,
            'userEmail' => $userEmail,
            'type' => 'reset_pwd',
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function verifyToken($token)
    {
        try {
            if ($token == null) {
                return "unauthorized";
            } else {
                $key = env('JWT_KEY');
                $decodedData = JWT::decode($token, new Key($key, 'HS256'));
                return $decodedData;
            }
        } catch (Exception $e) {
            return "unauthorized";
        }

    }
}

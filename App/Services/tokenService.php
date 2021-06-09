<?php

namespace App\Services;

use \Firebase\JWT\JWT;

class tokenService
{

    public function get()
    {
        $headers = getAuthorizationHeader();
        # HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public function create($user)
    {
        $payload = ['user_id' => $user->id];
        return $jwt = JWT::encode($payload, JWT_KEY, JWT_ALG);
    }

    public function isValid($jwt_token)
    {
        try {
            $payload = JWT::decode($jwt_token, JWT_KEY, array(JWT_ALG));
            $user = (new userService)->getUserById($payload->user_id);
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
}

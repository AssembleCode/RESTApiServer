<?php

namespace App\Services;

class JwtService
{
    protected $token;

    function generateTokens($userInfo, $userCredentials = [])
    {
        try {
            $userData = [
                'id'                 => $userInfo->id,
                'uuid'               => $userInfo->uuid,
                'name'               => $userInfo->name,
                'phone'              => $userInfo->phone,
                'email'              => $userInfo->email,
                'remember_token'     => $userInfo->remember_token,
                'status'             => $userInfo->status,
            ];
            return $userData;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

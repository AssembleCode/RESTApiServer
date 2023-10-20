<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;

class JwtService
{
    protected $token;

    public function generateTokens($userInfo)
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

            $accessToken  = $this->createAccessToken($userData);
            $refreshToken = $this->createRefreshToken($userData);
            return $accessToken;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    protected function createAccessToken($userData)
    {
        // PAYLOAD GENERATE
        $expires = config('jwt.expires.access');
        $expires = $expires === 0 ? null : time() + ($expires * 60);
        $expDateTime = Carbon::createFromTimestamp($expires)->toDateTimeString();

        $payload = [
            'sub' => $userData,
            'iat' => time(),
            'exp' => $expires,
            'jti' => Str::random(10),
        ];
        return [
            'token'   => $this->createToken($payload),
            'expires' => $expDateTime
        ];
    }

    protected function createRefreshToken($userData)
    {
        // PAYLOAD GENERATE
        $expires = config('jwt.expires.refresh');
        $expires = $expires === 0 ? null : time() + ($expires * 60);
        $expDateTime = Carbon::createFromTimestamp($expires)->toDateTimeString();

        $payload = [
            'sub' => $userData,
            'iat' => time(),
            'exp' => $expires,
            'jti' => Str::random(10),
        ];

        return [
            'token'   => $this->createToken($payload),
            'expires' => $expDateTime
        ];
    }

    protected function createToken($payload)
    {
        // HEADER PART
        $headerEncodedPart = $this->base64Url_encode(json_encode(config('jwt.header')));
        // PAYLOAD PART
        $payloadEncodedPart = $this->base64Url_encode(json_encode($payload));

        // SIGNATURE PART
        $signature = hash_hmac('SHA256', "{$headerEncodedPart}.{$payloadEncodedPart}", config('jwt.key'), true);
        $signatureEncodedPart = $this->base64Url_encode($signature);
        $token = "{$headerEncodedPart}.{$payloadEncodedPart}.{$signatureEncodedPart}";
        return $token;
    }

    // NEEDED CORE FUNCTIONS
    protected function base64Url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function base64Url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}

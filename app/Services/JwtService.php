<?php

namespace App\Services;

use App\Models\OAuthAccessToken;
use App\Models\OAuthRefreshToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class JwtService
{
    protected $token;

    // GET & SET TOKEN FROM REQUEST
    public function getTokenFromRequest()
    {
        $request = request();

        if ($this->token != null) {
            return $this->token;
        }

        $token = null;
        if (empty($this->token)) {
            $token = $request->bearerToken();
        }

        return $this->token = $token;
    }

    public function generateTokens($userInfo)
    {
        try {
            // DON'T SHARE SENSITIVE INFO (LIKE AS PASSWORD)
            $userData = [
                'id'                 => $userInfo->id,
                'uuid'               => $userInfo->uuid,
                'name'               => $userInfo->name,
                'phone'              => $userInfo->phone,
                'email'              => $userInfo->email,
                'remember_token'     => $userInfo->remember_token,
                'status'             => $userInfo->status,
            ];

            $accessTokenArray  = $this->createAccessToken($userData);
            $refreshTokenArray = $this->createRefreshToken($userData);
            //ACCESS_TOKEN INSERT
            $accessTokenInfo = OAuthAccessToken::create([
                'user_id'      => $userInfo->id,
                'name'         => $userInfo->name,
                'access_token' => $accessTokenArray['token'],
                'revoked'      => false,
                'expires_at'   => $accessTokenArray['expires']
            ]);

            //REFRESH_TOKEN INSERT
            OAuthRefreshToken::create([
                'user_id'         => $userInfo->id,
                'access_token_id' => $accessTokenInfo->id,
                'refresh_token'   => $refreshTokenArray['token'],
                'revoked'         => false,
                'expires_at'      => $refreshTokenArray['expires']
            ]);

            return [
                'access_token'  => $accessTokenArray['token'],
                'refresh_token' => $refreshTokenArray['token']
            ];
        } catch (Exception $exception) {
            throw $exception;
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

    // DECODE TOKEN PAYLOAD PART
    public function getReadablePayloadFromToken($token)
    {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) != 3) return null;

        return json_decode($this->base64Url_decode($tokenParts[1]));
    }

    // USER INFO FROM TOKEN PAYLOAD
    public function getUserInfoFromToken($token): object|null
    {
        // $token = $this->getTokenFromRequest();
        if ($token) {
            $readablePayload = $this->getReadablePayloadFromToken($token);
            return !empty($readablePayload) && isset($readablePayload) ? $readablePayload->sub : null;
        }
        return null;
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

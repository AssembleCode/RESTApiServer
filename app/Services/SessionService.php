<?php

namespace App\Services;

use App\Repositories\OAuthAccessTokenRepository;
use Carbon\Carbon;

class SessionService
{
    private $jwtService;

    private $token;

    private $userInfo;

    private $oAuthAccessTokenRepository;

    public function __construct()
    {
        $this->jwtService = new JwtService();
        $this->oAuthAccessTokenRepository = new OAuthAccessTokenRepository();
    }

    public function init()
    {
        $this->loadSessionData();
        return $this;
    }

    // VERIFY TOKEN EXPIRES & REVOKED
    protected function loadSessionData(): void
    {
        $token = $this->jwtService->getTokenFromRequest();
        $accessTokenInfo = $this->oAuthAccessTokenRepository->firstWhere("access_token", $token);
        $currentDateTime = Carbon::now()->toDateTimeString();

        if (!$accessTokenInfo->revoked && $accessTokenInfo->expires_at >= $currentDateTime) {
            $userInfo = $this->jwtService->getUserInfoFromToken($token);
            $this->token = $token;
            $this->userInfo = $userInfo;
        } else {
            $this->token = null;
            $this->userInfo = null;
        }
    }

    public function getSessionUserToken(): string|null
    {
        return $this->token;
    }

    public function getSessionUserInfo(): object|null
    {
        return $this->userInfo;
    }
}

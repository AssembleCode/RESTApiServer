<?php

namespace App\Repositories;

use App\Models\OAuthAccessToken;

class OAuthAccessTokenRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new OAuthAccessToken();
    }

    public function revokeToken($token)
    {
        return $this->newQuery()
            ->firstWhere("access_token", $token)
            ->update(['revoked' => true]);
    }

    public function revokePreviousTokens($userId)
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->update(['revoked' => true]);
    }
}

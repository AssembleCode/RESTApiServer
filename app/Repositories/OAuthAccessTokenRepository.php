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
        $this->model->firstWhere("access_token", $token)->update(['revoked' => true]);
    }
}

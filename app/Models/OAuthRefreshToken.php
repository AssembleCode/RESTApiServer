<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OAuthRefreshToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'access_token_id', 'refresh_token', 'revoked', 'expires_at'];

    protected $casts = [
        // Integer
        'id'                => 'integer',
        'user_id'           => 'integer',
        'access_token_id'   => 'integer',
        'revoked'           => 'integer',
        //Date Time
        'created_at'        => 'datetime:Y-m-d H:i:s',
        'updated_at'        => 'datetime:Y-m-d H:i:s',
        'expires_at'        => 'datetime:Y-m-d H:i:s',
        // String
        'name'              => 'string',
        'refresh_token'     => 'string',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'expires_at'
    ];
}

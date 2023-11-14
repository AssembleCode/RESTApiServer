<?php

namespace App\Models;

use App\Traits\Model\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examples extends Model
{
    use HasFactory, Uuid;

    protected $fillable = ['title', 'description', 'status'];

    protected $casts = [
        // INTEGER
        'id'           => 'integer',
        'status'       => 'integer',
        //DATE TIME
        'created_at'   => 'datetime:Y-m-d H:i:s',
        'updated_at'   => 'datetime:Y-m-d H:i:s',
        'expires_at'   => 'datetime:Y-m-d H:i:s',
        // STRING
        'title'        => 'string',
        'description'  => 'string',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}

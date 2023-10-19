<?php

namespace App\Traits\Model;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

trait Uuid
{
    protected static function bootUuid()
    {
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'uuid')) {
                $model->uuid = Str::uuid();
            }
        });
    }
}

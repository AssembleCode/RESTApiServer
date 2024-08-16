<?php

namespace App\Traits\Model;

use Illuminate\Support\Facades\Schema;
use App\Services\JwtService;

trait Autofill
{
    protected static function bootAutofill()
    {
        // static::creating(function($model)  {
        //     $jwtService = new JwtService();
        //     $tokenInfo = $jwtService->getTokeInfo();
        //     $userId = isset($tokenInfo->id) ? $tokenInfo->id : null;
        //     $organizationId = isset($tokenInfo->organization_id) ? $tokenInfo->organization_id : null;
        //     $organogramId = isset($tokenInfo->organogram_id) ? $tokenInfo->organogram_id : null;

        //     if (Schema::hasColumn($model->getTable(), 'user_id')) {
        //         $model->user_id = isset($model->user_id) ? $model->user_id : $userId;
        //     }
        //     if (Schema::hasColumn($model->getTable(), 'created_by')) {
        //         $model->created_by = isset($tokenInfo->id) ? $tokenInfo->id : null;
        //     }
        //     if (Schema::hasColumn($model->getTable(), 'updated_by')) {
        //         $model->updated_by = isset($tokenInfo->id) ? $tokenInfo->id : null;
        //     }
        //     if (Schema::hasColumn($model->getTable(), 'organization_id')) {
        //         $model->organization_id = isset($model->organization_id) ? $model->organization_id : $organizationId;
        //     }
        //     if (Schema::hasColumn($model->getTable(), 'organogram_id')) {
        //         $model->organogram_id = isset($model->organogram_id) ? $model->organogram_id : $organogramId;
        //     }
        // });

        // static::updating(function($model)  {
        //     $jwtService = new JwtService();
        //     $tokenInfo = $jwtService->getTokeInfo();
        //     if (Schema::hasColumn($model->getTable(), 'updated_by')) {
        //         $model->updated_by = isset($tokenInfo->id) ? $tokenInfo->id : null;
        //     }
        // });

        // static::deleting(function($model) {
        //     $jwtService = new JwtService();
        //     $tokenInfo = $jwtService->getTokeInfo();
        //     if (Schema::hasColumn($model->getTable(), 'deleted_by')) {
        //         $model->deleted_by = isset($tokenInfo->id) ? $tokenInfo->id : null;
        //         $model->save();
        //     }
        // });
    }
}

<?php

namespace App\Models\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            static::logAudit('create', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            static::logAudit('update', $model, $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            static::logAudit('delete', $model, $model->getAttributes(), null);
        });
    }

    protected static function logAudit($action, $model, $oldValue, $newValue)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'ancienne_val' => $oldValue,
            'nouvelle_val' => $newValue,
            'ip_address' => Request::ip(),
        ]);
    }
}

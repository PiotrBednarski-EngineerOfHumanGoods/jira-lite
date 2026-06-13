<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            self::recordAudit($model, 'created', $model->getAttributes());
        });

        static::updated(function ($model) {
            self::recordAudit($model, 'updated', $model->getChanges());
        });

        static::deleted(function ($model) {
            self::recordAudit($model, 'deleted', null);
        });
    }

    protected static function recordAudit($model, string $action, ?array $changes): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => $model::class,
            'auditable_id' => $model->getKey(),
            'changes' => $changes,
            'created_at' => now(),
        ]);
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}

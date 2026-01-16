<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->audit('create', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $old = array_intersect_key($model->getOriginal(), $model->getChanges());
            $new = $model->getChanges();
            
            // Don't log if no meaningful changes (excluding timestamps)
            unset($old['updated_at'], $new['updated_at']);
            
            if (!empty($new)) {
                $model->audit('update', $old, $new);
            }
        });

        static::deleted(function ($model) {
            $model->audit('delete', $model->getAttributes(), null);
        });
    }

    protected function audit($event, $old, $new)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}

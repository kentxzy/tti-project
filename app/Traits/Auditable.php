<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Boot the trait — automatically logs created, updated, deleted events.
     */
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::logAudit('created', $model, null, $model->getAttributes());
        });

        static::updated(function (Model $model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getAttributes();

            // Only log fields that actually changed
            $changes = [];
            foreach ($newValues as $key => $value) {
                if (array_key_exists($key, $oldValues) && $oldValues[$key] !== $value) {
                    $changes[$key] = $value;
                }
            }

            if (!empty($changes)) {
                self::logAudit('updated', $model, $oldValues, $newValues);
            }
        });

        static::deleted(function (Model $model) {
            self::logAudit('deleted', $model, $model->getAttributes(), null);
        });

        // Support soft-delete restore if the model uses SoftDeletes
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive(static::class))) {
            static::restored(function (Model $model) {
                self::logAudit('restored', $model, null, $model->getAttributes());
            });
        }
    }

    /**
     * Core method to create an AuditLog record.
     */
    public static function logAudit(
        string $action,
        Model $model,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): void {
        try {
            AuditLog::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'model_type'  => get_class($model),
                'model_id'    => $model->id,
                'old_values'  => $oldValues,
                'new_values'  => $newValues,
                'ip_address'  => Request::ip(),
                'user_agent'  => Request::userAgent(),
                'description' => $description,
            ]);
        } catch (\Exception $e) {
            \Log::error('Audit log creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Manually log a custom action on this model instance.
     * Useful for actions like 'viewed', 'exported', etc.
     *
     * Usage: $order->logAction('viewed', 'Customer viewed order details');
     */
    public function logAction(
        string $action,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        self::logAudit($action, $this, $oldValues, $newValues, $description);
    }

    /**
     * Get all audit logs for this model instance.
     */
    public function auditLogs()
    {
        return AuditLog::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get the most recent audit log for this model instance.
     */
    public function latestAuditLog(): ?AuditLog
    {
        return AuditLog::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->latest('created_at')
            ->first();
    }
}

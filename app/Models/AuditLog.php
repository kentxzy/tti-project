<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the auditable model
     */
    public function getAuditableModel()
    {
        if (!$this->model_type) {
            return null;
        }

        return $this->model_type::find($this->model_id);
    }

    /**
     * Scope to filter by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by model type
     */
    public function scopeByModel($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by role
     */
    public function scopeByRole($query, $role)
    {
       return $query->whereHas('user', function ($subQuery) use ($role) {
                 $subQuery->where('role', $role);
             });
    }

    /**
     * Scope for recent audit logs
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get a human-readable action label
     */
    public function getActionLabel(): string
    {
        return match($this->action) {
            'created' => '✅ Created',
            'updated' => '✏️ Updated',
            'deleted' => '🗑️ Deleted',
            'viewed' => '👁️ Viewed',
            'downloaded' => '📥 Downloaded',
            'exported' => '📤 Exported',
            'imported' => '📦 Imported',
            'logged_in' => '🔐 Logged In',
            'logged_out' => '🚪 Logged Out',
            'password_changed' => '🔑 Password Changed',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get the model type label
     */
    public function getModelLabel(): string
    {
        $models = [
            'App\Models\Order' => 'Order',
            'App\Models\OrderItem' => 'Order Item',
            'App\Models\Product' => 'Product',
            'App\Models\Inventory' => 'Inventory',
            'App\Models\Ticket' => 'Ticket',
            'App\Models\Branch' => 'Branch',
            'App\Models\User' => 'User',
        ];

        return $models[$this->model_type] ?? $this->model_type;
    }

    /**
     * Get changed fields summary
     */
    public function getChangedFields(): array
    {
        $changes = [];
        
        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
            }
        }

        return $changes;
    }
}

<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branch_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Role helpers ─────────────────────────────────────────────────────────

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isManager(): bool
    {
        return $this->role === 'branch_manager';
    }

    public function isSalesRep(): bool
    {
        return $this->role === 'sales_rep';
    }

    public function isDispatcher(): bool
    {
        // Fixed: was 'Dispatcher' (capital D) — must match the seeded value
        return $this->role === 'dispatcher';
    }

    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    /**
     * Returns true for any staff role (everyone except customer).
     * Fixed: was incorrectly checking for 'customer'.
     */
    public function isStaff(): bool
    {
        return in_array($this->role, [
            'branch_manager',
            'sales_rep',
            'dispatcher',
            'technician',
        ]);
    }
}

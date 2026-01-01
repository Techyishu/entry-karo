<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'password',
        'role',
        'customer_id',
        'organization_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is an admin (includes super_admin for backward compatibility)
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    /**
     * Check if user is a guard
     */
    public function isGuard(): bool
    {
        return $this->role === 'guard';
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Get the customer that this guard belongs to.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get all guards assigned to this customer.
     */
    public function guards(): HasMany
    {
        return $this->hasMany(User::class, 'customer_id');
    }

    /**
     * Get all entries processed by this guard.
     */
    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class, 'guard_id');
    }

    /**
     * Get all subscriptions for this customer.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscription for this customer.
     */
    public function activeSubscription()
    {
        return $this->hasMany(Subscription::class)
            ->where('status', 'active')
            ->latest()
            ->first();
    }

    /**
     * Check if customer has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()->where('status', 'active')->exists();
    }


    /**
     * Check if user can delete entries.
     * Only super admin can delete entries.
     */
    public function canDeleteEntries(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Check if user can manage guards.
     * Super admin and customers can manage guards.
     */
    public function canManageGuards(): bool
    {
        return $this->isSuperAdmin() || $this->isCustomer();
    }
}

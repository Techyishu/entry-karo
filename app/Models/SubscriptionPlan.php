<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'max_guards',
        'max_entries',
        'max_gate_logins',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'max_guards' => 'integer',
            'max_entries' => 'integer',
            'max_gate_logins' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get all subscriptions for this plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get active subscriptions for this plan.
     */
    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->where('status', 'active');
    }

    /**
     * Check if plan has unlimited guards.
     */
    public function hasUnlimitedGuards(): bool
    {
        return is_null($this->max_guards);
    }

    /**
     * Check if plan has unlimited entries.
     */
    public function hasUnlimitedEntries(): bool
    {
        return is_null($this->max_entries);
    }

    /**
     * Check if plan has unlimited gate logins.
     */
    public function hasUnlimitedGateLogins(): bool
    {
        return is_null($this->max_gate_logins);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format((float) $this->price, 2);
    }

    /**
     * Get entries display text.
     */
    public function getEntriesDisplayAttribute(): string
    {
        return $this->hasUnlimitedEntries() ? 'Unlimited' : number_format($this->max_entries);
    }

    /**
     * Get guards display text.
     */
    public function getGuardsDisplayAttribute(): string
    {
        return $this->hasUnlimitedGuards() ? 'Unlimited' : number_format($this->max_guards);
    }

    /**
     * Get gate logins display text.
     */
    public function getGateLoginsDisplayAttribute(): string
    {
        return $this->hasUnlimitedGateLogins() ? 'Unlimited' : number_format($this->max_gate_logins);
    }
}

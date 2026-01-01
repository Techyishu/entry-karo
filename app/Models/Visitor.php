<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'mobile_number',
        'name',
        'address',
        'purpose',
        'vehicle_number',
        'photo_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get all entries for this visitor.
     */
    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    /**
     * Get latest entry for this visitor.
     */
    public function latestEntry()
    {
        return $this->hasOne(Entry::class)->latestOfMany();
    }

    /**
     * Get current/active entry (if visitor hasn't checked out).
     */
    public function activeEntry()
    {
        return $this->hasOne(Entry::class)->whereNull('out_time');
    }

    /**
     * Check if visitor is currently checked in.
     */
    public function isCheckedIn(): bool
    {
        return $this->activeEntry()->exists();
    }

    /**
     * Get visitor's location history (all places they've visited).
     * Returns collection of entries with organization details.
     */
    public function getLocationHistory()
    {
        return $this->entries()
            ->with(['guardUser.customer'])
            ->latest('in_time')
            ->get()
            ->map(function ($entry) {
                $customer = $entry->guardUser->customer ?? $entry->guardUser;
                return [
                    'id' => $entry->id,
                    'organization_name' => $customer->name,
                    'organization_type' => $customer->organization_type ?? 'N/A',
                    'in_time' => $entry->in_time,
                    'out_time' => $entry->out_time,
                    'duration' => $entry->duration_minutes,
                    'guard_name' => $entry->guardUser->name,
                ];
            });
    }

    /**
     * Get unique locations visited by this visitor.
     */
    public function getUniqueLocationsCount(): int
    {
        return $this->entries()
            ->with('guardUser.customer')
            ->get()
            ->pluck('guardUser.customer.id')
            ->filter()
            ->unique()
            ->count();
    }
}

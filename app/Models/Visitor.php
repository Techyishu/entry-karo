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
}

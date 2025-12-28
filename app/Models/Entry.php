<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'visitor_id',
        'guard_id',
        'in_time',
        'out_time',
        'duration_minutes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'in_time' => 'datetime',
            'out_time' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the visitor for this entry.
     */
    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    /**
     * Get the guard who processed this entry.
     */
    public function guardUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guard_id');
    }

    /**
     * Get all carry items for this entry.
     */
    public function carryItems(): HasMany
    {
        return $this->hasMany(CarryItem::class);
    }

    /**
     * Check if the visitor is currently checked in.
     */
    public function isCheckedIn(): bool
    {
        return is_null($this->out_time);
    }

    /**
     * Check if the visitor has checked out.
     */
    public function hasCheckedOut(): bool
    {
        return !is_null($this->out_time);
    }

    /**
     * Calculate and set the duration in minutes.
     */
    public function calculateDuration(): void
    {
        if ($this->out_time) {
            $duration = $this->in_time->diffInMinutes($this->out_time);
            $this->duration_minutes = $duration;
            $this->save();
        }
    }
}

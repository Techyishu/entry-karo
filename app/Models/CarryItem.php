<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarryItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'entry_id',
        'item_name',
        'item_type',
        'quantity',
        'item_photo_path',
        'in_status',
        'out_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'in_status' => 'boolean',
            'out_status' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the entry this item belongs to.
     */
    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    /**
     * Get the visitor through the entry.
     */
    public function visitor()
    {
        return $this->entry->visitor;
    }

    /**
     * Check if item was brought in.
     */
    public function wasBroughtIn(): bool
    {
        return $this->in_status;
    }

    /**
     * Check if item was taken out.
     */
    public function wasTakenOut(): bool
    {
        return $this->out_status;
    }

    /**
     * Check if item is still inside.
     */
    public function isInside(): bool
    {
        return $this->in_status && !$this->out_status;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MoldModification extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_model_id',
        'model_name', 
        'spec_before',
        'spec_after',
        'production_date',
        'status',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'production_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime'
        ];
    }

    /**
     * Get asset model that owns mold modification.
     */
    public function assetModel(): BelongsTo
    {
        return $this->belongsTo(AssetModel::class);
    }

    /**
     * Get is critical attribute (H-7 warning).
     */
    public function getIsCriticalAttribute(): bool
    {
        return $this->production_date->diffInDays(now(), false) <= 7 
               && $this->production_date->isFuture()
               && $this->status === 'pending';
    }

    /**
     * Get days remaining attribute.
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->production_date->isPast()) {
            return 0;
        }
        return $this->production_date->diffInDays(now(), false);
    }

    /**
     * Get status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'done' => 'Selesai',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        if ($this->is_critical) {
            return 'danger';
        }
        
        return match($this->status) {
            'pending' => 'warning',
            'done' => 'success',
            default => 'secondary'
        };
    }

    /**
     * Get row CSS class for table display.
     */
    public function getRowClassAttribute(): string
    {
        if ($this->is_critical) {
            return 'bg-red-50';
        }
        
        if ($this->status === 'done') {
            return 'bg-green-50';
        }
        
        return '';
    }

    /**
     * Get text CSS class for table display.
     */
    public function getTextClassAttribute(): string
    {
        if ($this->is_critical) {
            return 'text-red-700';
        }
        
        if ($this->status === 'done') {
            return 'text-green-700';
        }
        
        return '';
    }

    /**
     * Scope to get only critical items (H-7 warning).
     */
    public function scopeCritical($query)
    {
        return $query->where('status', 'pending')
                    ->where('production_date', '<=', now()->addDays(7))
                    ->where('production_date', '>', now());
    }

    /**
     * Scope to get only pending items.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get only completed items.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'done');
    }

    /**
     * Scope to order by production date.
     */
    public function scopeOrderByProductionDate($query, $direction = 'asc')
    {
        return $query->orderBy('production_date', $direction);
    }
}
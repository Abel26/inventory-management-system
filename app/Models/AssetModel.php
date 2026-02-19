<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'model_code',
        'name',
        'type',
        'quantity',
        'location',
        'gedung_id',
        'supplier',
        'manufacture_date',
        'description',
        'qr_code_path',
        'condition',
        'unit_price',
        'material_id', // Added missing field
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'manufacture_date' => 'date',
            'condition' => 'string',
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
        ];
    }

    /**
     * Get the asset material that this model belongs to.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(AssetMaterial::class, 'material_id');
    }

    /**
     * Get all reports for this model.
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Get all mold modifications for this model.
     */
    public function moldModifications(): HasMany
    {
        return $this->hasMany(MoldModification::class);
    }

    /**
     * Get pending mold modifications for this model.
     */
    public function pendingMoldModifications(): HasMany
    {
        return $this->moldModifications()->pending();
    }

    /**
     * Get critical mold modifications (H-7 warning) for this model.
     */
    public function criticalMoldModifications(): HasMany
    {
        return $this->moldModifications()->critical();
    }

    /**
     * Get condition label (Indonesian).
     */
    public function getConditionLabelAttribute(): string
    {
        return match($this->condition) {
            'Good' => 'Baik',
            'Repair' => 'Perbaikan',
            'Damaged' => 'Rusak',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get condition badge color.
     */
    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'Good' => 'success',
            'Repair' => 'warning',
            'Damaged' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get condition status for search results
     */
    public function getConditionStatusAttribute(): string
    {
        return match($this->condition) {
            'Good' => 'in_stock',
            'Repair' => 'maintenance',
            'Damaged' => 'damaged',
            default => 'unknown',
        };
    }

    /**
     * Check if this model has critical mold modifications.
     */
    public function hasCriticalModifications(): bool
    {
        return $this->criticalMoldModifications()->exists();
    }

    /**
     * Get count of pending modifications.
     */
    public function getPendingModificationsCountAttribute(): int
    {
        return $this->pendingMoldModifications()->count();
    }

    /**
     * Get the gedung that this model belongs to.
     */
    public function gedung(): BelongsTo
    {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }
}

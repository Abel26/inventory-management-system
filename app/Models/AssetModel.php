<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'manufactured_date' => 'date',
            'condition' => 'string',
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AssetTool extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tool_code',
        'name',
        'category',
        'brand',
        'type',
        'purchase_date',
        'purchase_price',
        'purchase_year',
        'quantity',
        'unit',
        'location',
        'gedung_id',
        'supplier',
        'warranty_expiry',
        'condition',
        'description',
        'qr_code_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
            'purchase_year' => 'integer',
            'quantity' => 'integer',
            'warranty_expiry' => 'date',
            'condition' => 'string',
            'unit_price' => 'decimal:2',
        ];
    }

    /**
     * Get all reports for this tool.
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Get condition label.
     */
    public function getConditionLabelAttribute(): string
    {
        return match($this->condition) {
            'Good' => 'Baik',
            'Repair' => 'Perbaikan',
            'Damaged' => 'Rusak',
            'Disposed' => 'Dibuang',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get condition color.
     */
    public function getConditionColorAttribute(): string
    {
        $colors = [
            'Good' => 'green',
            'Repair' => 'yellow',
            'Damaged' => 'red',
            'Disposed' => 'gray',
        ];
        return $colors[$this->condition] ?? 'gray';
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
            'Disposed' => 'disposed',
            default => 'unknown',
        };
    }
}

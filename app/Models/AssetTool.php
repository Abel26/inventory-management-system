<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'purchase_year',
        'quantity',
        'location',
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
            'purchase_year' => 'integer',
            'quantity' => 'integer',
            'condition' => 'string',
        ];
    }

    /**
     * Get condition label.
     */
    public function getConditionLabelAttribute(): string
    {
        $labels = [
            'Good' => 'Baik',
            'Repair' => 'Perbaikan',
            'Damaged' => 'Rusak',
            'Disposed' => 'Dibuang',
        ];
        return $labels[$this->condition] ?? $this->condition;
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
}

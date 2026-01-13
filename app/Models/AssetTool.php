<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTool extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'category',
        'brand',
        'model_type',
        'purchase_year',
        'quantity',
        'unit',
        'min_threshold',
        'location',
        'condition',
        'description',
        'image',
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
            'min_threshold' => 'integer',
            'condition' => 'string',
        ];
    }
}

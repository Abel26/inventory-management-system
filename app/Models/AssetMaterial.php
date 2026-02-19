<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AssetMaterial extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'material_code',
        'name',
        'type',
        'quantity',
        'unit',
        'unit_id', // Tambahkan unit_id untuk relasi ke satuan
        'min_threshold',
        'supplier',
        'entry_date',
        'expiry_date',
        'location',
        'gedung_id',
        'description',
        'unit_price',
        'qr_code_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'min_threshold' => 'integer',
        'unit_id' => 'integer', // Tambahkan casting untuk unit_id
        'entry_date' => 'date',
        'expiry_date' => 'date',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Get the satuan for this material.
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'unit_id', 'id');
    }

    /**
     * Get the gedung for this material.
     */
    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'gedung_id', 'gedung_id');
    }

    /**
     * Get the models that use this material.
     */
    public function models(): BelongsToMany
    {
        return $this->belongsToMany(AssetModel::class, 'asset_model_material', 'material_id', 'model_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get all reports for this material.
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Check if material is low stock.
     */
    public function isLowStock(): bool
    {
        return $this->quantity < $this->min_threshold;
    }

    /**
     * Check if material is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    /**
     * Get stock status.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    /**
     * Get stock status label (Indonesian).
     */
    public function getStockStatusLabelAttribute(): string
    {
        return match($this->stock_status) {
            'out_of_stock' => 'Habis',
            'low_stock' => 'Menipis',
            'in_stock' => 'Tersedia',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get stock status badge color.
     */
    public function getStockStatusColorAttribute(): string
    {
        return match($this->stock_status) {
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'in_stock' => 'success',
            default => 'secondary',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gedung extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gedung_id',
        'nama',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gedung_id' => 'string',
            'nama' => 'string',
        ];
    }

    /**
     * Get the asset models that belong to this gedung.
     */
    public function assetModels(): HasMany
    {
        return $this->hasMany(AssetModel::class, 'gedung_id', 'gedung_id');
    }

    /**
     * Get the asset materials that belong to this gedung.
     */
    public function assetMaterials(): HasMany
    {
        return $this->hasMany(AssetMaterial::class, 'gedung_id', 'gedung_id');
    }

    /**
     * Get the asset tools that belong to this gedung.
     */
    public function assetTools(): HasMany
    {
        return $this->hasMany(AssetTool::class, 'gedung_id', 'gedung_id');
    }

    /**
     * Get total asset models count.
     */
    public function getAssetModelsCountAttribute(): int
    {
        return $this->assetModels()->count();
    }

    /**
     * Get total asset materials count.
     */
    public function getAssetMaterialsCountAttribute(): int
    {
        return $this->assetMaterials()->count();
    }

    /**
     * Get total asset tools count.
     */
    public function getAssetToolsCountAttribute(): int
    {
        return $this->assetTools()->count();
    }

    /**
     * Get total assets count.
     */
    public function getTotalAssetsAttribute(): int
    {
        return $this->assetModelsCount + $this->assetMaterialsCount + $this->assetToolsCount;
    }
}
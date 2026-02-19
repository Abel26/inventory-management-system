<?php

namespace App\Services;

use App\Models\AssetMaterial;
use App\Repositories\Contracts\AssetMaterialRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class AssetMaterialService
{
    /**
     * AssetMaterial Repository instance.
     */
    public function __construct(
        protected AssetMaterialRepositoryInterface $assetMaterialRepository
    ) {
        $this->assetMaterialRepository = $assetMaterialRepository;
    }

    /**
     * Get all asset materials.
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->assetMaterialRepository->all();
    }

    /**
     * Find asset material by ID.
     */
    public function find(int $id): ?AssetMaterial
    {
        return $this->assetMaterialRepository->find($id);
    }

    /**
     * Find asset material by material code.
     */
    public function getByMaterialCode(string $code): ?AssetMaterial
    {
        return $this->assetMaterialRepository->findByCode($code);
    }

    /**
     * Create new asset material.
     */
    public function create(array $data): AssetMaterial
    {
        Log::info('MATERIAL SERVICE: Creating new material', ['input_data' => $data]);
        
        // Generate material code if not provided
        if (!isset($data['material_code']) || empty($data['material_code'])) {
            $data['material_code'] = $this->generateMaterialCode();
            Log::info('MATERIAL SERVICE: Generated material code', ['material_code' => $data['material_code']]);
        }

        try {
            // Create material
            Log::info('MATERIAL SERVICE: Calling repository create');
            $material = $this->assetMaterialRepository->create($data);
            Log::info('MATERIAL SERVICE: Material created successfully', ['material_id' => $material->id]);

            // Generate QR Code
            Log::info('MATERIAL SERVICE: Generating QR code');
            $this->generateQrCode($material);
            Log::info('MATERIAL SERVICE: QR code generated successfully');

            return $material->fresh();
        } catch (\Exception $e) {
            Log::error('MATERIAL SERVICE: Error creating material', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Update existing asset material.
     */
    public function update(int $id, array $data): bool
    {
        $material = $this->find($id);
        
        if (!$material) {
            return false;
        }

        $result = $this->assetMaterialRepository->update($material, $data);

        // Regenerate QR Code if material code changed
        if (isset($data['material_code']) && $data['material_code'] !== $material->getOriginal('material_code')) {
            $this->generateQrCode($material);
        }

        return $result;
    }

    /**
     * Delete asset material.
     */
    public function delete(int $id): bool
    {
        $material = $this->find($id);
        
        if (!$material) {
            return false;
        }

        // Delete QR Code file
        $this->deleteQrCode($material);

        return $this->assetMaterialRepository->delete($material);
    }

    /**
     * Search asset materials.
     */
    public function search(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return $this->assetMaterialRepository->search($query);
    }

    /**
     * Check if asset material is low stock.
     */
    public function isLowStock(AssetMaterial $material): bool
    {
        return $material->isLowStock();
    }

    /**
     * Generate unique material code.
     * Format: MAT-YYYYMMDD-XXX
     */
    protected function generateMaterialCode(): string
    {
        $datePrefix = now()->format('Ymd');
        $randomSuffix = strtoupper(Str::random(3));
        
        $code = "MAT-{$datePrefix}-{$randomSuffix}";

        // Check if code already exists, regenerate if needed
        while ($this->assetMaterialRepository->findByCode($code)) {
            $randomSuffix = strtoupper(Str::random(3));
            $code = "MAT-{$datePrefix}-{$randomSuffix}";
        }

        return $code;
    }

    /**
     * Generate QR Code for material.
     */
    protected function generateQrCode(AssetMaterial $material): void
    {
        try {
            // Generate QR Code as SVG
            $qrCode = QrCode::format('svg')
                ->size(150)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($material->material_code);

            // Store QR Code in storage
            $path = "qrcodes/materials/{$material->material_code}.svg";
            Storage::disk('public')->put($path, $qrCode);
        } catch (\Exception $e) {
            // Log error but don't fail the operation
            Log::error('Gagal generate QR Code untuk material: ' . $material->material_code, [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete QR Code file for material.
     */
    protected function deleteQrCode(AssetMaterial $material): void
    {
        try {
            $path = "qrcodes/materials/{$material->material_code}.svg";
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghapus QR Code untuk material: ' . $material->material_code, [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get QR Code URL for material.
     */
    public function getQrCodeUrl(AssetMaterial $material): string
    {
        $path = "qrcodes/materials/{$material->material_code}.svg";
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        // Generate QR Code if not exists
        $this->generateQrCode($material);
        
        return Storage::url($path);
    }

    /**
     * Get QR Code HTML for display in modal.
     */
    public function getQrCodeHtml(AssetMaterial $material): string
    {
        try {
            $path = "qrcodes/materials/{$material->material_code}.svg";
            
            if (!Storage::disk('public')->exists($path)) {
                $this->generateQrCode($material);
            }
            
            $qrCodeContent = Storage::disk('public')->get($path);
            
            return '<img src="data:image/svg+xml;base64,' . base64_encode($qrCodeContent) . '" alt="QR Code" class="w-48 h-48">';
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan QR Code untuk material: ' . $material->material_code, [
                'error' => $e->getMessage()
            ]);
            return '<p class="text-red-500">QR Code tidak tersedia</p>';
        }
    }

    /**
     * Get materials with low stock.
     */
    public function getLowStockMaterials(): \Illuminate\Database\Eloquent\Collection
    {
        return AssetMaterial::whereColumn('quantity', '<', 'min_threshold')
            ->where('quantity', '>', 0)
            ->get();
    }

    /**
     * Get materials out of stock.
     */
    public function getOutOfStockMaterials(): \Illuminate\Database\Eloquent\Collection
    {
        return AssetMaterial::where('quantity', '<=', 0)->get();
    }

    /**
     * Get total value of all materials.
     */
    public function getTotalValue(): float
    {
        return AssetMaterial::selectRaw('SUM(quantity * unit_price) as total')
            ->value('total') ?? 0;
    }
}

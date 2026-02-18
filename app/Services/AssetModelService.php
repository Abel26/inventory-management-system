<?php

namespace App\Services;

use App\Models\AssetModel;
use App\Repositories\Contracts\AssetModelRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class AssetModelService
{
    /**
     * AssetModel Repository instance.
     */
    public function __construct(
        protected AssetModelRepositoryInterface $assetModelRepository
    ) {
        $this->assetModelRepository = $assetModelRepository;
    }

    /**
     * Get all asset models.
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->assetModelRepository->all();
    }

    /**
     * Find asset model by ID.
     */
    public function find(int $id): ?AssetModel
    {
        return $this->assetModelRepository->find($id);
    }

    /**
     * Find asset model by model code.
     */
    public function getByModelCode(string $code): ?AssetModel
    {
        return $this->assetModelRepository->findByCode($code);
    }

    /**
     * Create new asset model.
     */
    public function create(array $data): AssetModel
    {
        // Generate model code if not provided
        if (!isset($data['model_code']) || empty($data['model_code'])) {
            $data['model_code'] = $this->generateModelCode();
        }

        // Create model
        $model = $this->assetModelRepository->create($data);

        // Generate QR Code
        $this->generateQrCode($model);

        return $model->fresh();
    }

    /**
     * Update existing asset model.
     */
    public function update(int $id, array $data): bool
    {
        $model = $this->find($id);
        
        if (!$model) {
            return false;
        }

        $model->update($data);

        // Regenerate QR Code if model code changed
        if (isset($data['model_code']) && $data['model_code'] !== $model->getOriginal('model_code')) {
            $this->generateQrCode($model);
        }

        return true;
    }

    /**
     * Delete asset model.
     */
    public function delete(int $id): bool
    {
        $model = $this->find($id);
        
        if (!$model) {
            return false;
        }

        // Delete QR Code file
        $this->deleteQrCode($model);

        $model->delete();

        return true;
    }

    /**
     * Search asset models.
     */
    public function search(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return $this->assetModelRepository->search($query);
    }

    /**
     * Generate unique model code.
     * Format: MOD-YYYYMMDD-XXX
     */
    protected function generateModelCode(): string
    {
        $datePrefix = now()->format('Ymd');
        $randomSuffix = strtoupper(Str::random(3));
        
        $code = "MOD-{$datePrefix}-{$randomSuffix}";

        // Check if code already exists, regenerate if needed
        while ($this->assetModelRepository->findByCode($code)) {
            $randomSuffix = strtoupper(Str::random(3));
            $code = "MOD-{$datePrefix}-{$randomSuffix}";
        }

        return $code;
    }

    /**
     * Generate QR Code for model.
     */
    protected function generateQrCode(AssetModel $model): void
    {
        try {
            // Generate QR Code as SVG
            $qrCode = QrCode::format('svg')
                ->size(150)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($model->model_code);

            // Store QR Code in storage
            $path = "qrcodes/models/{$model->model_code}.svg";
            Storage::disk('public')->put($path, $qrCode);
        } catch (\Exception $e) {
            // Log error but don't fail the operation
            Log::error('Gagal generate QR Code untuk model: ' . $model->model_code, [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete QR Code file for model.
     */
    protected function deleteQrCode(AssetModel $model): void
    {
        try {
            $path = "qrcodes/models/{$model->model_code}.svg";
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghapus QR Code untuk model: ' . $model->model_code, [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get QR Code URL for model.
     */
    public function getQrCodeUrl(AssetModel $model): string
    {
        $path = "qrcodes/models/{$model->model_code}.svg";
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        // Generate QR Code if not exists
        $this->generateQrCode($model);
        
        return Storage::url($path);
    }

    /**
     * Get QR Code HTML for display in modal.
     */
    public function getQrCodeHtml(AssetModel $model): string
    {
        try {
            $path = "qrcodes/models/{$model->model_code}.svg";
            
            if (!Storage::disk('public')->exists($path)) {
                $this->generateQrCode($model);
            }
            
            $qrCodeContent = Storage::disk('public')->get($path);
            
            return '<img src="data:image/svg+xml;base64,' . base64_encode($qrCodeContent) . '" alt="QR Code" class="w-48 h-48">';
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan QR Code untuk model: ' . $model->model_code, [
                'error' => $e->getMessage()
            ]);
            return '<p class="text-red-500">QR Code tidak tersedia</p>';
        }
    }
}

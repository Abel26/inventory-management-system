<?php

namespace App\Services;

use App\Models\AssetTool;
use App\Repositories\Contracts\AssetToolRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class AssetToolService
{
    /**
     * AssetTool Repository instance.
     */
    public function __construct(
        protected AssetToolRepositoryInterface $assetToolRepository
    ) {
        $this->assetToolRepository = $assetToolRepository;
    }

    /**
     * Get all asset tools.
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return AssetTool::withoutTrashed()->get();
    }

    /**
     * Find asset tool by ID.
     */
    public function find(int $id): ?AssetTool
    {
        return $this->assetToolRepository->find($id);
    }

    /**
     * Find asset tool by tool code.
     */
    public function getByToolCode(string $code): ?AssetTool
    {
        return $this->assetToolRepository->findByCode($code);
    }

    /**
     * Create new asset tool.
     */
    public function create(array $data): AssetTool
    {
        // Generate tool code if not provided
        if (!isset($data['tool_code']) || empty($data['tool_code'])) {
            $data['tool_code'] = $this->generateToolCode();
        }

        // Create tool
        $tool = $this->assetToolRepository->create($data);

        // Generate QR Code
        $this->generateQrCode($tool);

        return $tool->fresh();
    }

    /**
     * Update existing asset tool.
     */
    public function update(int $id, array $data): bool
    {
        $tool = $this->find($id);
        
        if (!$tool) {
            return false;
        }

        $tool->update($data);

        // Regenerate QR Code if tool code changed
        if (isset($data['tool_code']) && $data['tool_code'] !== $tool->getOriginal('tool_code')) {
            $this->generateQrCode($tool);
        }

        return true;
    }

    /**
     * Delete asset tool.
     */
    public function delete(int $id): bool
    {
        $tool = $this->find($id);
        
        if (!$tool) {
            return false;
        }

        // Delete QR Code file
        $this->deleteQrCode($tool);

        $tool->delete();

        return true;
    }

    /**
     * Search asset tools.
     */
    public function search(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return $this->assetToolRepository->search($query);
    }

    /**
     * Generate unique tool code.
     * Format: TOL-YYYYMMDD-XXX
     */
    protected function generateToolCode(): string
    {
        $datePrefix = now()->format('Ymd');
        $randomSuffix = strtoupper(Str::random(3));
        
        $code = "TOL-{$datePrefix}-{$randomSuffix}";

        // Check if code already exists, regenerate if needed
        while ($this->assetToolRepository->findByCode($code)) {
            $randomSuffix = strtoupper(Str::random(3));
            $code = "TOL-{$datePrefix}-{$randomSuffix}";
        }

        return $code;
    }

    /**
     * Generate QR Code for tool.
     */
    protected function generateQrCode(AssetTool $tool): void
    {
        try {
            // Generate QR Code as SVG
            $qrCode = QrCode::format('svg')
                ->size(150)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($tool->tool_code);

            // Store QR Code in storage
            $path = "qrcodes/tools/{$tool->tool_code}.svg";
            Storage::disk('public')->put($path, $qrCode);
            
            // Update tool with QR code path
            $tool->update(['qr_code_path' => $path]);
        } catch (\Exception $e) {
            // Log error but don't fail operation
            Log::error('Gagal generate QR Code untuk tool: ' . $tool->tool_code, [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete QR Code file for tool.
     */
    protected function deleteQrCode(AssetTool $tool): void
    {
        try {
            $path = "qrcodes/tools/{$tool->tool_code}.svg";
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghapus QR Code untuk tool: ' . $tool->tool_code, [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get QR Code URL for tool.
     */
    public function getQrCodeUrl(AssetTool $tool): string
    {
        $path = "qrcodes/tools/{$tool->tool_code}.svg";
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        // Generate QR Code if not exists
        $this->generateQrCode($tool);
        
        return Storage::url($path);
    }

    /**
     * Get QR Code HTML for display in modal.
     */
    public function getQrCodeHtml(AssetTool $tool): string
    {
        try {
            $path = "qrcodes/tools/{$tool->tool_code}.svg";
            
            if (!Storage::disk('public')->exists($path)) {
                $this->generateQrCode($tool);
            }
            
            $qrCodeContent = Storage::disk('public')->get($path);
            
            return '<img src="data:image/svg+xml;base64,' . base64_encode($qrCodeContent) . '" alt="QR Code" class="w-48 h-48">';
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan QR Code untuk tool: ' . $tool->tool_code, [
                'error' => $e->getMessage()
            ]);
            return '<p class="text-red-500">QR Code tidak tersedia</p>';
        }
    }

    /**
     * Get tools with good condition.
     */
    public function getGoodConditionTools(): \Illuminate\Database\Eloquent\Collection
    {
        return AssetTool::where('condition', 'Good')->get();
    }

    /**
     * Get tools in repair.
     */
    public function getRepairTools(): \Illuminate\Database\Eloquent\Collection
    {
        return AssetTool::where('condition', 'Repair')->get();
    }

    /**
     * Get damaged tools.
     */
    public function getDamagedTools(): \Illuminate\Database\Eloquent\Collection
    {
        return AssetTool::where('condition', 'Damaged')->get();
    }

    /**
     * Get disposed tools.
     */
    public function getDisposedTools(): \Illuminate\Database\Eloquent\Collection
    {
        return AssetTool::where('condition', 'Disposed')->get();
    }
}

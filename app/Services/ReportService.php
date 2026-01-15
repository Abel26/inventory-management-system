<?php

namespace App\Services;

use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function __construct(
        protected ReportRepositoryInterface $reportRepository
    ) {
        $this->reportRepository = $reportRepository;
    }
    
    /**
     * Get all reports with relationships.
     */
    public function getAll()
    {
        return $this->reportRepository->allWithRelations();
    }
    
    /**
     * Create new report.
     */
    public function create(array $data)
    {
        // Generate unique report code
        $data['report_code'] = $this->generateReportCode($data['issue_type']);
        
        // Handle photo upload
        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $data['photo']->store('reports/photos', 'public');
            $data['photo_path'] = $path;
        }
        
        return $this->reportRepository->create($data);
    }
    
    /**
     * Find report by ID.
     */
    public function find(int $id)
    {
        return $this->reportRepository->find($id);
    }
    
    /**
     * Get reports by status.
     */
    public function getByStatus(string $status)
    {
        return $this->reportRepository->getByStatus($status);
    }
    
    /**
     * Update report status.
     */
    public function updateStatus(int $id, string $status, ?int $resolvedBy = null)
    {
        $data = [
            'status' => $status,
        ];
        
        if ($status === 'Resolved') {
            $data['resolved_at'] = now();
            $data['resolved_by'] = $resolvedBy ?? auth()->id();
        }
        
        return $this->reportRepository->update($id, $data);
    }
    
    /**
     * Generate unique report code.
     */
    private function generateReportCode(string $issueType): string
    {
        $prefixes = [
            'Damage' => 'DMG',
            'Maintenance' => 'MNT',
            'Lost' => 'LST',
            'Stock Discrepancy' => 'STK',
        ];
        
        $prefix = $prefixes[$issueType] ?? 'RPT';
        $date = date('Ymd');
        $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        
        return $prefix . $date . '-' . $random;
    }
}

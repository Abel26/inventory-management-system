<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGedungRequest;
use App\Http\Requests\UpdateGedungRequest;
use App\Models\Gedung;
use App\Exports\GedungsExport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;

class GedungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $gedungs = Gedung::withCount(['assetModels', 'assetMaterials', 'assetTools'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('gedungs.index', compact('gedungs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('gedungs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGedungRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $gedung = Gedung::create($request->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data gedung berhasil ditambahkan',
                'data' => $gedung
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data gedung: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $gedung = Gedung::withCount(['assetModels', 'assetMaterials', 'assetTools'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $gedung
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data gedung tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $gedung = Gedung::findOrFail($id);
        return view('gedungs.edit', compact('gedung'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGedungRequest $request, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $gedung = Gedung::findOrFail($id);
            $gedung->update($request->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data gedung berhasil diperbarui',
                'data' => $gedung
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data gedung: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $gedung = Gedung::findOrFail($id);
            $gedung->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data gedung berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data gedung: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get data for DataTables.
     */
    public function getData(Request $request): JsonResponse
    {
        $gedungs = Gedung::withCount(['assetModels', 'assetMaterials', 'assetTools'])
            ->orderBy('created_at', 'desc');

        // Filter by search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $gedungs->where(function($query) use ($search) {
                $query->where('gedung_id', 'like', "%{$search}%")
                      ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $totalRecords = $gedungs->count();
        $gedungs = $gedungs->skip($request->start)
                           ->take($request->length)
                           ->get();

        $data = $gedungs->map(function ($gedung) {
            return [
                'id' => $gedung->id,
                'gedung_id' => $gedung->gedung_id,
                'nama' => $gedung->nama,
                'asset_models_count' => $gedung->asset_models_count,
                'asset_materials_count' => $gedung->asset_materials_count,
                'asset_tools_count' => $gedung->asset_tools_count,
                'total_assets' => $gedung->asset_models_count + $gedung->asset_materials_count + $gedung->asset_tools_count,
                'created_at' => $gedung->created_at->format('d/m/Y H:i'),
                'updated_at' => $gedung->updated_at->format('d/m/Y H:i'),
            ];
        });

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    /**
     * Export gedungs to Excel.
     */
    public function export()
    {
        try {
            // Increase execution time for export
            set_time_limit(300); // 5 minutes
            ini_set('memory_limit', '512M');
            
            Log::info('Starting gedung export process');
            
            $filename = 'gedungs-' . date('Y-m-d') . '.xlsx';
            
            Log::info('Generating Excel file: ' . $filename);
            
            // Use fast export with optimized settings
            return Excel::download(new GedungsExport(), $filename);
            
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengekspor data gedung: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal mengekspor data gedung: ' . $e->getMessage());
        }
    }

    /**
     * Export gedungs to PDF.
     */
    public function exportPdf()
    {
        try {
            // Increase execution time for export
            set_time_limit(300); // 5 minutes
            ini_set('memory_limit', '512M');
            
            Log::info('Starting gedung PDF export process');
            
            $gedungs = Gedung::withCount(['assetModels', 'assetMaterials', 'assetTools'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            Log::info('Found ' . $gedungs->count() . ' gedungs for PDF export');
            
            $date = Carbon::now()->locale('id')->isoFormat('D MMMM Y');
            $title = 'Laporan Data Gedung';
            $subtitle = 'Data Seluruh Gedung dan Total Aset';
            
            Log::info('Generating PDF file');
            
            $pdf = PDF::loadView('gedungs.pdf', compact('gedungs', 'date', 'title', 'subtitle'))
                ->setPaper('a4', 'portrait')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', true);
            
            return $pdf->download("Laporan Gedung {$date}.pdf");
            
        } catch (\Exception $e) {
            Log::error('PDF export error: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengekspor PDF gedung: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal mengekspor PDF gedung: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft deleted gedung.
     */
    public function restore(string $id): JsonResponse
    {
        try {
            $gedung = Gedung::withTrashed()->findOrFail($id);
            $gedung->restore();

            return response()->json([
                'success' => true,
                'message' => 'Data gedung berhasil dipulihkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan data gedung: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get trashed gedungs.
     */
    public function trashed(): View
    {
        $gedungs = Gedung::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('gedungs.trashed', compact('gedungs'));
    }
}
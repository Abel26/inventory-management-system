<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGedungRequest;
use App\Http\Requests\UpdateGedungRequest;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

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
        $gedung = Gedung::withCount(['assetModels', 'assetMaterials', 'assetTools'])
            ->find($id);

        if (!$gedung) {
            return response()->json([
                'success' => false,
                'message' => 'Data gedung tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $gedung
        ]);
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
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = Gedung::withCount(['assetModels', 'assetMaterials', 'assetTools']);

        // Apply search
        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('gedung_id', 'like', "%{$search}%");
        }

        // Get total records
        $totalRecords = $query->count();

        // Apply pagination
        $gedungs = $query->offset($start)
            ->limit($length)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $gedungs->map(function ($gedung) {
            return [
                'id' => $gedung->id,
                'gedung_id' => $gedung->gedung_id,
                'nama' => $gedung->nama,
                'asset_models_count' => $gedung->assetModelsCount,
                'asset_materials_count' => $gedung->assetMaterialsCount,
                'asset_tools_count' => $gedung->assetToolsCount,
                'total_assets' => $gedung->assetModelsCount + $gedung->assetMaterialsCount + $gedung->assetToolsCount,
                'created_at' => $gedung->created_at->format('d/m/Y H:i'),
                'updated_at' => $gedung->updated_at->format('d/m/Y H:i'),
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ]);
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
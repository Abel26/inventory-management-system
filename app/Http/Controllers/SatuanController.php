<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('satuans.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('satuans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:satuans,nama',
            'kode' => 'required|string|max:50|unique:satuans,kode',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Creating satuan with data:', [
                'nama' => $request->nama,
                'kode' => $request->kode
            ]);
            
            $satuan = Satuan::create([
                'nama' => $request->nama,
                'kode' => $request->kode,
            ]);

            Log::info('Satuan created successfully:', ['id' => $satuan->id]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan berhasil ditambahkan',
                'data' => [
                    'id' => $satuan->id,
                    'nama' => $satuan->nama,
                    'kode' => $satuan->kode,
                    'created_at' => $satuan->formatted_created_at,
                    'updated_at' => $satuan->formatted_updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating satuan:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan satuan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            Log::info('Getting satuan with ID:', ['id' => $id]);
            
            // Find the satuan manually
            $satuan = Satuan::find($id);
            
            // Ensure the satuan exists
            if (!$satuan) {
                Log::error('Satuan not found with ID:', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data satuan tidak ditemukan'
                ], 404);
            }

            Log::info('Satuan found:', [
                'id' => $satuan->id,
                'nama' => $satuan->nama,
                'kode' => $satuan->kode
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $satuan->id,
                    'nama' => $satuan->nama,
                    'kode' => $satuan->kode,
                    'created_at' => $satuan->formatted_created_at,
                    'updated_at' => $satuan->formatted_updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting satuan:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data satuan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Satuan $satuan): View
    {
        return view('satuans.edit', compact('satuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Find the satuan manually
        $satuan = Satuan::find($id);
        
        if (!$satuan) {
            Log::error('Satuan not found for update with ID:', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Data satuan tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:satuans,nama,' . $satuan->id,
            'kode' => 'required|string|max:50|unique:satuans,kode,' . $satuan->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Updating satuan with data:', [
                'id' => $satuan->id,
                'nama' => $request->nama,
                'kode' => $request->kode
            ]);
            
            $satuan->update([
                'nama' => $request->nama,
                'kode' => $request->kode,
            ]);

            Log::info('Satuan updated successfully:', ['id' => $satuan->id]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating satuan:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui satuan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Find the satuan manually
            $satuan = Satuan::find($id);
            
            if (!$satuan) {
                Log::error('Satuan not found for delete with ID:', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data satuan tidak ditemukan'
                ], 404);
            }

            Log::info('Deleting satuan:', ['id' => $satuan->id]);
            
            $satuan->delete();

            Log::info('Satuan deleted successfully:', ['id' => $satuan->id]);

            return response()->json([
                'success' => true,
                'message' => 'Satuan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting satuan:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus satuan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data for DataTables.
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search')['value'] ?? '';

            $query = Satuan::query();

            // Apply search
            if ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            }

            $totalRecords = $query->count();
            $satuans = $query->offset($start)
                ->limit($length)
                ->orderBy('nama')
                ->get();

            $data = [];
            foreach ($satuans as $index => $satuan) {
                $data[] = [
                    'DT_RowIndex' => $start + $index + 1,
                    'id' => $satuan->id,
                    'nama' => $satuan->nama,
                    'kode' => $satuan->kode,
                    'created_at' => $satuan->formatted_created_at,
                    'updated_at' => $satuan->formatted_updated_at,
                ];
            }

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
}
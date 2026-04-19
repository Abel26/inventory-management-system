<?php

namespace App\Http\Controllers;

use App\Models\AssetMaterial;
use App\Models\AssetTool;
use App\Models\AssetModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class LandingController extends Controller
{
    /**
     * Display landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch real database counts for landing page
        $stats = [
            'materials' => AssetMaterial::count(),
            'tools' => AssetTool::count(),
            'models' => AssetModel::count(),
            'users' => User::count(),
            'delays' => '0%',
        ];
        
        // Always show landing page with real data
        return view('landing', compact('stats'));
    }

    /**
     * Search assets for public access.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'total' => 0
            ]);
        }
        
        $results = [];
        
        // Search Materials with proper field names
        $materials = AssetMaterial::where('name', 'LIKE', "%{$query}%")
            ->orWhere('material_code', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'material_code', 'qr_code_path')
            ->limit(3)
            ->get();
            
        foreach ($materials as $material) {
            $results[] = [
                'id' => $material->id,
                'type' => 'material',
                'name' => $material->name,
                'code' => $material->material_code,
                'status' => 'available', // Lowercase for translation key
                'image' => $material->qr_code_path ? asset('storage/' . $material->qr_code_path) : null,
                'status_color' => $this->getStatusColor('available')
            ];
        }
        
        // Search Tools with proper field names
        $tools = AssetTool::where('name', 'LIKE', "%{$query}%")
            ->orWhere('tool_code', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'tool_code', 'condition', 'qr_code_path')
            ->limit(3)
            ->get();
            
        foreach ($tools as $tool) {
            $results[] = [
                'id' => $tool->id,
                'type' => 'tool',
                'name' => $tool->name,
                'code' => $tool->tool_code,
                'status' => strtolower($tool->condition ?? 'available'), // Lowercase for translation key
                'image' => $tool->qr_code_path ? asset('storage/' . $tool->qr_code_path) : null,
                'status_color' => $this->getStatusColor($tool->condition ?? 'available')
            ];
        }
        
        // Search Models with proper field names
        $models = AssetModel::where('name', 'LIKE', "%{$query}%")
            ->orWhere('model_code', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'model_code', 'qr_code_path')
            ->limit(3)
            ->get();
            
        foreach ($models as $model) {
            $results[] = [
                'id' => $model->id,
                'type' => 'model',
                'name' => $model->name,
                'code' => $model->model_code,
                'status' => 'available', // Lowercase for translation key
                'image' => $model->qr_code_path ? asset('storage/' . $model->qr_code_path) : null,
                'status_color' => $this->getStatusColor('available')
            ];
        }
        
        return response()->json([
            'results' => $results,
            'total' => count($results)
        ]);
    }

    /**
     * Get asset detail for public access.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssetDetail(Request $request)
    {
        $id = $request->get('id');
        $type = $request->get('type');
        
        if (!$id || !$type) {
            return response()->json(['message' => 'Missing parameters'], 400);
        }
        
        $asset = null;
        
        switch (strtolower($type)) {
            case 'material':
                $asset = AssetMaterial::find($id);
                break;
            case 'tool':
                $asset = AssetTool::find($id);
                break;
            case 'model':
                $asset = AssetModel::find($id);
                break;
        }
        
        if (!$asset) {
            return response()->json(['message' => 'Asset not found'], 404);
        }
        
        // Prepare public detail data (omitting sensitive info if any)
        $detail = [
            'id' => $asset->id,
            'type' => ucfirst($type),
            'name' => $asset->name,
            'code' => $asset->material_code ?? $asset->tool_code ?? $asset->model_code,
            'description' => $asset->description ?? '-',
            'location' => $asset->location ?? '-',
            'status' => strtolower($asset->condition ?? 'available'),
            'status_color' => $this->getStatusColor($asset->condition ?? 'available'),
            'image' => $asset->qr_code_path ? asset('storage/' . $asset->qr_code_path) : null,
            'category' => $asset->category ?? $asset->type ?? '-',
            'quantity' => $asset->quantity ?? 0,
            'unit' => $asset->unit ?? 'pcs',
        ];
        
        return response()->json($detail);
    }


    /**
     * Get status color based on status
     *
     * @param string $status
     * @return string
     */
    private function getStatusColor($status)
    {
        switch (strtolower($status)) {
            case 'available':
                return 'green';
            case 'good':
                return 'blue';
            case 'maintenance':
                return 'yellow';
            case 'damaged':
                return 'red';
            case 'lost':
                return 'red';
            default:
                return 'gray';
        }
    }
}
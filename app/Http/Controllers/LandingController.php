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
            ->select('name', 'material_code', 'qr_code_path')
            ->limit(3)
            ->get();
            
        foreach ($materials as $material) {
            $results[] = [
                'type' => 'Material',
                'name' => $material->name,
                'code' => $material->material_code,
                'status' => 'Available', // Default status
                'image' => $material->qr_code_path ? asset('storage/' . $material->qr_code_path) : null,
                'status_color' => $this->getStatusColor('Available')
            ];
        }
        
        // Search Tools with proper field names
        $tools = AssetTool::where('name', 'LIKE', "%{$query}%")
            ->orWhere('tool_code', 'LIKE', "%{$query}%")
            ->select('name', 'tool_code', 'condition', 'qr_code_path')
            ->limit(3)
            ->get();
            
        foreach ($tools as $tool) {
            $results[] = [
                'type' => 'Tool',
                'name' => $tool->name,
                'code' => $tool->tool_code,
                'status' => $tool->condition, // Use condition field as status
                'image' => $tool->qr_code_path ? asset('storage/' . $tool->qr_code_path) : null,
                'status_color' => $this->getStatusColor($tool->condition)
            ];
        }
        
        // Search Models with proper field names
        $models = AssetModel::where('name', 'LIKE', "%{$query}%")
            ->orWhere('model_code', 'LIKE', "%{$query}%")
            ->select('name', 'model_code', 'qr_code_path')
            ->limit(3)
            ->get();
            
        foreach ($models as $model) {
            $results[] = [
                'type' => 'Model',
                'name' => $model->name,
                'code' => $model->model_code,
                'status' => 'Available', // Default status
                'image' => $model->qr_code_path ? asset('storage/' . $model->qr_code_path) : null,
                'status_color' => $this->getStatusColor('Available')
            ];
        }
        
        return response()->json([
            'results' => $results,
            'total' => count($results)
        ]);
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
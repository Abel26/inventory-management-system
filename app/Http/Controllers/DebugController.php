<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\AssetModel;
use App\Models\AssetMaterial;
use App\Models\MoldModification;

class DebugController extends Controller
{
    /**
     * Test database consistency
     */
    public function testDatabaseConsistency(): JsonResponse
    {
        $results = [];
        
        // Test 1: Check AssetModel vs AssetMaterial relationship
        try {
            Log::info('DEBUG: Testing AssetModel-Material relationship');
            
            // Check if material_id exists in asset_models table
            $assetModels = AssetModel::with('material')->limit(5)->get();
            $relationshipIssues = [];
            
            foreach ($assetModels as $model) {
                if ($model->material_id && !$model->material) {
                    $relationshipIssues[] = [
                        'model_id' => $model->id,
                        'model_code' => $model->model_code,
                        'material_id' => $model->material_id,
                        'issue' => 'material_id exists but relationship not working'
                    ];
                }
            }
            
            $results['asset_model_relationship'] = [
                'total_models_tested' => $assetModels->count(),
                'relationship_issues' => $relationshipIssues,
                'status' => empty($relationshipIssues) ? 'OK' : 'ISSUES_FOUND'
            ];
            
        } catch (\Exception $e) {
            Log::error('DEBUG: AssetModel relationship test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $results['asset_model_relationship'] = [
                'error' => $e->getMessage(),
                'status' => 'ERROR'
            ];
        }
        
        // Test 2: Check MoldModification queries
        try {
            Log::info('DEBUG: Testing MoldModification queries');
            
            $criticalMods = MoldModification::critical()->get();
            $pendingMods = MoldModification::pending()->get();
            
            $results['mold_modification_queries'] = [
                'critical_count' => $criticalMods->count(),
                'pending_count' => $pendingMods->count(),
                'total_count' => MoldModification::count(),
                'status' => 'OK'
            ];
            
            // Test is_critical attribute
            $criticalAttributeIssues = [];
            foreach ($criticalMods as $mod) {
                if (!$mod->is_critical) {
                    $criticalAttributeIssues[] = [
                        'id' => $mod->id,
                        'production_date' => $mod->production_date,
                        'status' => $mod->status,
                        'issue' => 'Query says critical but attribute says false'
                    ];
                }
            }
            
            $results['mold_modification_critical_attribute'] = [
                'issues' => $criticalAttributeIssues,
                'status' => empty($criticalAttributeIssues) ? 'OK' : 'ISSUES_FOUND'
            ];
            
        } catch (\Exception $e) {
            Log::error('DEBUG: MoldModification query test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $results['mold_modification_queries'] = [
                'error' => $e->getMessage(),
                'status' => 'ERROR'
            ];
        }
        
        // Test 3: Check database schema consistency
        try {
            Log::info('DEBUG: Testing database schema consistency');
            
            // Check if columns exist
            $assetModelColumns = DB::getSchemaBuilder()->getColumnListing('asset_models');
            $assetMaterialColumns = DB::getSchemaBuilder()->getColumnListing('asset_materials');
            $moldModColumns = DB::getSchemaBuilder()->getColumnListing('mold_modifications');
            
            $schemaIssues = [];
            
            // Check AssetModel expected columns
            $expectedAssetModelColumns = ['id', 'model_code', 'name', 'type', 'material_id', 'manufacture_date', 'condition', 'location', 'description', 'qr_code_path', 'created_at', 'updated_at', 'deleted_at'];
            $missingAssetModelColumns = array_diff($expectedAssetModelColumns, $assetModelColumns);
            
            if (!empty($missingAssetModelColumns)) {
                $schemaIssues['asset_models_missing_columns'] = $missingAssetModelColumns;
            }
            
            // Check for date field inconsistency
            if (in_array('manufacture_date', $assetModelColumns) && in_array('manufactured_date', $assetModelColumns)) {
                $schemaIssues['asset_models_date_confusion'] = 'Both manufacture_date and manufactured_date exist';
            }
            
            $results['database_schema'] = [
                'asset_models_columns' => $assetModelColumns,
                'asset_materials_columns' => $assetMaterialColumns,
                'mold_modifications_columns' => $moldModColumns,
                'schema_issues' => $schemaIssues,
                'status' => empty($schemaIssues) ? 'OK' : 'ISSUES_FOUND'
            ];
            
        } catch (\Exception $e) {
            Log::error('DEBUG: Database schema test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $results['database_schema'] = [
                'error' => $e->getMessage(),
                'status' => 'ERROR'
            ];
        }
        
        // Test 4: Check controller consistency
        try {
            Log::info('DEBUG: Testing controller architecture consistency');
            
            $controllerFiles = [
                'MoldModificationController' => app_path('Http/Controllers/MoldModificationController.php'),
                'AssetManagementController' => app_path('Http/Controllers/AssetManagementController.php')
            ];
            
            $architectureIssues = [];
            
            foreach ($controllerFiles as $name => $path) {
                if (file_exists($path)) {
                    $content = file_get_contents($path);
                    
                    if ($name === 'MoldModificationController') {
                        // Check if using Service layer
                        if (strpos($content, 'Service') === false) {
                            $architectureIssues[] = "$name: Not using Service layer";
                        }
                        
                        // Check if using Repository pattern
                        if (strpos($content, 'Repository') === false) {
                            $architectureIssues[] = "$name: Not using Repository pattern";
                        }
                    }
                    
                    if ($name === 'AssetManagementController') {
                        // Check dependency injection
                        if (strpos($content, '__construct') === false) {
                            $architectureIssues[] = "$name: Missing constructor for dependency injection";
                        }
                    }
                }
            }
            
            $results['controller_architecture'] = [
                'architecture_issues' => $architectureIssues,
                'status' => empty($architectureIssues) ? 'OK' : 'ISSUES_FOUND'
            ];
            
        } catch (\Exception $e) {
            Log::error('DEBUG: Controller architecture test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $results['controller_architecture'] = [
                'error' => $e->getMessage(),
                'status' => 'ERROR'
            ];
        }
        
        // Log summary
        Log::info('DEBUG: Database consistency test completed', [
            'results' => $results
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Debug test completed',
            'data' => $results,
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Test form validation consistency
     */
    public function testValidationConsistency(): JsonResponse
    {
        $results = [];
        
        try {
            Log::info('DEBUG: Testing form validation consistency');
            
            // Test MoldModification validation
            $moldModificationRequest = new \App\Http\Requests\StoreMoldModificationRequest();
            $rules = $moldModificationRequest->rules();
            $messages = $moldModificationRequest->messages();
            
            $validationIssues = [];
            
            // Check if authorization is properly implemented
            if ($moldModificationRequest->authorize() === true) {
                $validationIssues[] = 'StoreMoldModificationRequest: authorize() always returns true';
            }
            
            // Check if production_date validation is reasonable
            if (isset($rules['production_date'])) {
                if (strpos($rules['production_date'], 'after:today') !== false) {
                    $validationIssues[] = 'StoreMoldModificationRequest: production_date uses after:today (should be after_or_equal:today for same-day entries)';
                }
            }
            
            $results['validation_consistency'] = [
                'mold_modification_rules' => $rules,
                'mold_modification_messages' => $messages,
                'validation_issues' => $validationIssues,
                'status' => empty($validationIssues) ? 'OK' : 'ISSUES_FOUND'
            ];
            
        } catch (\Exception $e) {
            Log::error('DEBUG: Validation consistency test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $results['validation_consistency'] = [
                'error' => $e->getMessage(),
                'status' => 'ERROR'
            ];
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Validation test completed',
            'data' => $results,
            'timestamp' => now()->toISOString()
        ]);
    }
}
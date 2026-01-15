<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\User;
use App\Models\AssetTool;
use App\Services\AssetLookupService;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportModuleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected AssetTool $asset;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();

        // Create a test asset
        $this->asset = AssetTool::factory()->create([
            'tool_code' => 'TEST001',
            'name' => 'Test Tool',
            'location' => 'Gudang A',
            'condition' => 'Good',
        ]);

        // Authenticate the user
        $this->actingAs($this->user);
    }

    /**
     * Test report dashboard can be rendered.
     */
    public function test_report_dashboard_can_be_rendered(): void
    {
        // Create some test reports
        Report::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'reportable_id' => $this->asset->id,
            'reportable_type' => AssetTool::class,
        ]);

        $response = $this->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('reports.index');
        $response->assertViewHas('stats');
        $response->assertViewHas('reports');
    }

    /**
     * Test scan QR redirects to create page with asset data.
     */
    public function test_scan_qr_redirects_to_create_page_with_asset_data(): void
    {
        // Mock the AssetLookupService
        $this->mock(AssetLookupService::class, function ($mock) {
            $mock->shouldReceive('findByCode')
                ->with('TEST001')
                ->andReturn([
                    'type' => 'tool',
                    'model' => AssetTool::class,
                    'asset' => $this->asset,
                    'display_name' => 'Test Tool',
                    'code' => 'TEST001',
                    'condition' => 'Good',
                    'location' => 'Gudang A',
                ]);

            $mock->shouldReceive('getAssetDisplayInfo')
                ->andReturn([
                    'found' => true,
                    'asset' => [
                        'name' => 'Test Tool',
                        'code' => 'TEST001',
                        'type' => 'Tool',
                        'condition' => 'Good',
                        'location' => 'Gudang A',
                        'id' => $this->asset->id,
                        'model_class' => AssetTool::class,
                    ],
                ]);
        });

        $response = $this->get(route('reports.scan', ['code' => 'TEST001']));

        $response->assertRedirect(route('reports.create'));
        $response->assertSessionHas('asset');
        $response->assertSessionHas('asset_type');
    }

    /**
     * Test scan QR redirects to dashboard when asset not found.
     */
    public function test_scan_qr_redirects_to_dashboard_when_asset_not_found(): void
    {
        // Mock the AssetLookupService to return null
        $this->mock(AssetLookupService::class, function ($mock) {
            $mock->shouldReceive('findByCode')
                ->with('NOTFOUND')
                ->andReturn(null);

            $mock->shouldReceive('getAssetDisplayInfo')
                ->with(null)
                ->andReturn([
                    'found' => false,
                    'message' => 'Aset tidak ditemukan',
                ]);
        });

        $response = $this->get(route('reports.scan', ['code' => 'NOTFOUND']));

        $response->assertRedirect(route('reports.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test user can store report.
     */
    public function test_user_can_store_report(): void
    {
        Storage::fake('public');

        $reportData = [
            'reportable_id' => $this->asset->id,
            'reportable_type' => AssetTool::class,
            'issue_type' => 'Damage',
            'priority' => 'High',
            'description' => 'This is a test report description',
        ];

        $response = $this->post(route('reports.store'), $reportData);

        $response->assertRedirect(route('reports.index'));
        $response->assertSessionHas('success');

        // Verify the report was created in database
        $this->assertDatabaseHas('reports', [
            'user_id' => $this->user->id,
            'reportable_id' => $this->asset->id,
            'reportable_type' => AssetTool::class,
            'issue_type' => 'Damage',
            'priority' => 'High',
            'description' => 'This is a test report description',
        ]);
    }

    /**
     * Test user can store report with photo.
     */
    public function test_user_can_store_report_with_photo(): void
    {
        Storage::fake('public');

        $photo = \Illuminate\Http\UploadedFile::fake()->image('report.jpg', 1024);

        $reportData = [
            'reportable_id' => $this->asset->id,
            'reportable_type' => AssetTool::class,
            'issue_type' => 'Maintenance',
            'priority' => 'Medium',
            'description' => 'Test report with photo',
            'photo' => $photo,
        ];

        $response = $this->post(route('reports.store'), $reportData);

        $response->assertRedirect(route('reports.index'));
        $response->assertSessionHas('success');

        // Verify the report was created with photo
        $this->assertDatabaseHas('reports', [
            'user_id' => $this->user->id,
            'reportable_id' => $this->asset->id,
            'reportable_type' => AssetTool::class,
            'issue_type' => 'Maintenance',
            'priority' => 'Medium',
        ]);

        // Verify photo was stored
        $report = Report::where('user_id', $this->user->id)->first();
        $this->assertNotNull($report->photo_path);
        Storage::disk('public')->assertExists($report->photo_path);
    }

    /**
     * Test store report validation.
     */
    public function test_store_report_validation_requires_required_fields(): void
    {
        $invalidData = [
            'reportable_id' => '', // Missing
            'reportable_type' => '', // Missing
            'issue_type' => '', // Missing
            'priority' => '', // Missing
            'description' => '', // Missing
        ];

        $response = $this->post(route('reports.store'), $invalidData);

        $response->assertSessionHasErrors();
    }

    /**
     * Test store report validation.
     */
    public function test_store_report_validation(): void
    {
        $invalidData = [
            'reportable_id' => '', // Missing
            'reportable_type' => '', // Missing
            'issue_type' => '', // Missing
            'priority' => '', // Missing
            'description' => '', // Missing
        ];

        $response = $this->post(route('reports.store'), $invalidData);

        $response->assertSessionHasErrors();
    }

    /**
     * Test report show page can be rendered.
     */
    public function test_report_show_page_can_be_rendered(): void
    {
        $report = Report::factory()->create([
            'user_id' => $this->user->id,
            'reportable_id' => $this->asset->id,
            'reportable_type' => AssetTool::class,
        ]);

        $response = $this->get(route('reports.show', $report->id));

        $response->assertStatus(200);
        $response->assertViewIs('reports.show');
        $response->assertViewHas('report');
    }

    /**
     * Test report show page returns 404 for non-existent report.
     */
    public function test_report_show_page_returns_404_for_non_existent_report(): void
    {
        $response = $this->get(route('reports.show', 99999));

        $response->assertStatus(404);
    }

    /**
     * Test report status can be updated.
     */
    public function test_report_status_can_be_updated(): void
    {
        $report = Report::factory()->create([
            'user_id' => $this->user->id,
            'reportable_id' => $this->asset->id,
            'reportable_type' => AssetTool::class,
            'status' => 'Pending',
        ]);

        $response = $this->put(route('reports.update-status', $report->id), [
            'status' => 'Resolved',
        ]);

        $response->assertRedirect(route('reports.index'));
        $response->assertSessionHas('success');

        // Verify status was updated
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'Resolved',
            'resolved_by' => $this->user->id,
        ]);
    }

    /**
     * Test get reports by status (AJAX).
     */
    public function test_get_reports_by_status(): void
    {
        Report::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'reportable_id' => $this->asset->id,
            'reportable_type' => AssetTool::class,
            'status' => 'Pending',
        ]);

        Report::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'reportable_id' => $this->asset->id,
            'reportable_type' => AssetTool::class,
            'status' => 'Resolved',
        ]);

        $response = $this->getJson(route('reports.by-status', 'Pending'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $json = $response->json();
        $this->assertCount(3, $json['data']);
    }
}

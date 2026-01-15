<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use App\Models\AssetTool;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'report_code' => 'DMG' . date('Ymd') . '-' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT),
            'user_id' => User::factory(),
            'reportable_id' => AssetTool::factory(),
            'reportable_type' => AssetTool::class,
            'issue_type' => fake()->randomElement(['Damage', 'Maintenance', 'Lost', 'Stock Discrepancy']),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High', 'Critical']),
            'description' => fake()->text(),
            'photo_path' => null,
            'status' => fake()->randomElement(['Pending', 'In Progress', 'Resolved', 'Rejected']),
            'resolved_at' => null,
            'resolved_by' => null,
        ];
    }
}

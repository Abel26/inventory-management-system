<?php

namespace Database\Factories;

use App\Models\WorkLog;
use App\Enums\WorkStatus;
use App\Enums\WorkPriority;
use App\Enums\WorkType;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkLogFactory extends Factory
{
    protected $model = WorkLog::class;

    public function definition(): array
    {
        return [
            'work_code' => WorkLog::generateWorkCode(),
            'user_id' => \App\Models\User::factory(),
            'description' => fake()->sentence(),
            'work_date' => fake()->date(),
            'start_time' => fake()->time('H:i'),
            'end_time' => fake()->time('H:i'),
            'break_duration' => fake()->numberBetween(0, 60),
            'total_work_minutes' => fake()->numberBetween(60, 480),
            'status' => fake()->randomElement(WorkStatus::cases())->value,
            'priority' => fake()->randomElement(WorkPriority::cases())->value,
            'work_type' => fake()->randomElement(WorkType::cases())->value,
            'completion_percentage' => fake()->numberBetween(0, 100),
            'notes' => fake()->paragraph(),
            'location_id' => \App\Models\Gedung::factory(),
            'locked' => false,
        ];
    }
}

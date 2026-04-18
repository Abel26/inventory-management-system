<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Enums\WorkStatus;
use App\Enums\WorkPriority;
use App\Enums\WorkType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Normalize existing data to lowercase if any exists
        // This prevents errors when changing column types if the database is strict
        DB::table('work_logs')->update(['status' => DB::raw('LOWER(status)')]);
        DB::table('work_logs')->update(['priority' => DB::raw('LOWER(priority)')]);
        DB::table('work_logs')->update(['work_type' => DB::raw('LOWER(work_type)')]);

        // Fix specific mapping if needed (e.g. Critical -> urgent)
        DB::table('work_logs')->where('priority', 'critical')->update(['priority' => WorkPriority::URGENT->value]);
        
        // Handle work_type mapping which changed significantly
        // Mapping: Regular -> production, Overtime -> maintenance, Remote -> documentation, On-Site -> other
        DB::table('work_logs')->where('work_type', 'regular')->update(['work_type' => WorkType::PRODUCTION->value]);
        DB::table('work_logs')->where('work_type', 'overtime')->update(['work_type' => WorkType::MAINTENANCE->value]);
        DB::table('work_logs')->where('work_type', 'remote')->update(['work_type' => WorkType::DOCUMENTATION->value]);
        DB::table('work_logs')->where('work_type', 'on-site')->update(['work_type' => WorkType::OTHER->value]);

        // 2. Change columns to match Enums
        Schema::table('work_logs', function (Blueprint $table) {
            // Drop old enums and use new ones
            // Using string first to clear the enum constraints in some DB engines
            $table->string('status')->change();
            $table->string('priority')->change();
            $table->string('work_type')->change();
        });

        Schema::table('work_logs', function (Blueprint $table) {
            $table->enum('status', WorkStatus::values())->default(WorkStatus::PENDING->value)->change();
            $table->enum('priority', WorkPriority::values())->default(WorkPriority::MEDIUM->value)->change();
            $table->enum('work_type', WorkType::values())->default(WorkType::PRODUCTION->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_logs', function (Blueprint $table) {
            $table->string('status')->change();
            $table->string('priority')->change();
            $table->string('work_type')->change();
        });

        Schema::table('work_logs', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'On Hold', 'Cancelled'])->default('Pending')->change();
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium')->change();
            $table->enum('work_type', ['Regular', 'Overtime', 'Remote', 'On-Site'])->default('Regular')->change();
        });
    }
};

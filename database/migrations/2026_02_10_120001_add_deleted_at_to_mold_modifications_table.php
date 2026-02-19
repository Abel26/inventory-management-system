<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column exists before adding
        if (!Schema::hasColumn('mold_modifications', 'deleted_at')) {
            Schema::table('mold_modifications', function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if column exists
        if (Schema::hasColumn('mold_modifications', 'deleted_at')) {
            Schema::table('mold_modifications', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
    }
};

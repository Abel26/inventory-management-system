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
        Schema::table('asset_models', function (Blueprint $table) {
            // Rename manufactured_date to manufacture_date to match model
            $table->renameColumn('manufactured_date', 'manufacture_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_models', function (Blueprint $table) {
            // Rename back to original
            $table->renameColumn('manufacture_date', 'manufactured_date');
        });
    }
};
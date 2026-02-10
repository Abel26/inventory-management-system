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
        // Add gedung_id to asset_materials table
        Schema::table('asset_materials', function (Blueprint $table) {
            $table->string('gedung_id', 50)->nullable()->after('location')->comment('Kode gedung lokasi material');
            $table->foreign('gedung_id')->references('gedung_id')->on('gedungs')->onDelete('set null');
            $table->index('gedung_id');
        });

        // Add gedung_id to asset_models table
        Schema::table('asset_models', function (Blueprint $table) {
            $table->string('gedung_id', 50)->nullable()->after('location')->comment('Kode gedung lokasi model');
            $table->foreign('gedung_id')->references('gedung_id')->on('gedungs')->onDelete('set null');
            $table->index('gedung_id');
        });

        // Add gedung_id to asset_tools table
        Schema::table('asset_tools', function (Blueprint $table) {
            $table->string('gedung_id', 50)->nullable()->after('location')->comment('Kode gedung lokasi tool');
            $table->foreign('gedung_id')->references('gedung_id')->on('gedungs')->onDelete('set null');
            $table->index('gedung_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove gedung_id from asset_materials table
        Schema::table('asset_materials', function (Blueprint $table) {
            $table->dropForeign(['gedung_id']);
            $table->dropIndex(['gedung_id']);
            $table->dropColumn('gedung_id');
        });

        // Remove gedung_id from asset_models table
        Schema::table('asset_models', function (Blueprint $table) {
            $table->dropForeign(['gedung_id']);
            $table->dropIndex(['gedung_id']);
            $table->dropColumn('gedung_id');
        });

        // Remove gedung_id from asset_tools table
        Schema::table('asset_tools', function (Blueprint $table) {
            $table->dropForeign(['gedung_id']);
            $table->dropIndex(['gedung_id']);
            $table->dropColumn('gedung_id');
        });
    }
};
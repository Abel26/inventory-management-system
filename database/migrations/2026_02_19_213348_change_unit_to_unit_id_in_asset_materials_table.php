<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asset_materials', function (Blueprint $table) {
            // Tambahkan kolom unit_id baru
            $table->unsignedBigInteger('unit_id')->nullable()->after('quantity');
            
            // Buat foreign key ke tabel satuans
            $table->foreign('unit_id')->references('id')->on('satuans')->onDelete('set null');
        });
        
        // Pindahkan data dari unit ke unit_id jika ada (jika ada data satuan yang cocok)
        // Ini adalah migrasi data yang aman, hanya memindahkan jika ada kecocokan
        DB::statement("
            UPDATE asset_materials
            SET unit_id = (
                SELECT id FROM satuans
                WHERE LOWER(satuans.kode) = LOWER(asset_materials.unit)
                OR LOWER(satuans.nama) = LOWER(asset_materials.unit)
                LIMIT 1
            )
            WHERE unit IS NOT NULL AND unit != ''
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_materials', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
};

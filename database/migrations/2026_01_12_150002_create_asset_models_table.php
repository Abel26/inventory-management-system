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
        Schema::create('asset_models', function (Blueprint $table) {
            $table->id();
            $table->string('model_code', 50)->unique()->comment('Kode unik model (M001, M002, dll)');
            $table->string('name')->comment('Nama model/cetakan');
            $table->string('type')->comment('Tipe (Injection, CNC, dll)');
            $table->foreignId('material_id')->nullable()->constrained('asset_materials')->nullOnDelete()->comment('Material terkait');
            $table->date('manufactured_date')->nullable()->comment('Tanggal pembuatan');
            $table->enum('condition', ['Good', 'Repair', 'Damaged'])->default('Good')->comment('Kondisi model');
            $table->string('location')->nullable()->comment('Lokasi penyimpanan (Gudang A, dll)');
            $table->text('description')->nullable()->comment('Deskripsi tambahan');
            $table->string('qr_code_path')->nullable()->comment('Path file QR Code');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('model_code');
            $table->index('material_id');
            $table->index('condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_models');
    }
};

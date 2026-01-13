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
        Schema::create('asset_materials', function (Blueprint $table) {
            $table->id();
            $table->string('material_code', 50)->unique()->comment('Kode material unik untuk QR Code');
            $table->string('name')->comment('Nama material');
            $table->string('type')->default('general')->comment('Tipe material');
            $table->integer('quantity')->default(0)->comment('Jumlah stok');
            $table->string('unit', 20)->comment('Satuan (pcs, kg, liter, dll)');
            $table->integer('min_threshold')->default(10)->comment('Batas minimum stok');
            $table->string('supplier')->nullable()->comment('Nama supplier');
            $table->date('entry_date')->comment('Tanggal masuk material');
            $table->date('expiry_date')->nullable()->comment('Tanggal kedaluwarsa (opsional)');
            $table->string('location')->nullable()->comment('Lokasi penyimpanan');
            $table->text('description')->nullable()->comment('Deskripsi material');
            $table->decimal('unit_price', 15, 2)->nullable()->comment('Harga per satuan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_materials');
    }
};

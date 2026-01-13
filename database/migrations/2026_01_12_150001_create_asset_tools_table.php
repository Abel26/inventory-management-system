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
        Schema::create('asset_tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('general');
            $table->string('brand')->nullable();
            $table->string('model_type')->nullable();
            $table->integer('purchase_year')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('location')->nullable();
            $table->enum('condition', ['baik', 'rusak', 'perbaikan'])->default('baik');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_tools');
    }
};

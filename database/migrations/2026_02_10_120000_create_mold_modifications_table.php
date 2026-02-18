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
        Schema::create('mold_modifications', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to AssetModel (mold/cetakan)
            $table->foreignId('asset_model_id')
                  ->constrained('asset_models')
                  ->onDelete('cascade')
                  ->comment('Reference to the mold/cetakan asset model');
            
            // Core modification data
            $table->string('model_name')
                  ->comment('Display name of the mold model (e.g., "Casing 150-315")');
            
            $table->string('spec_before')
                  ->comment('Current specification before modification (e.g., "PN 16")');
            
            $table->string('spec_after')
                  ->comment('Target specification after modification (e.g., "JIS 20 K")');
            
            // Critical deadline
            $table->date('production_date')
                  ->comment('Production date that requires modification completion');
            
            // Status tracking
            $table->enum('status', ['pending', 'done'])
                  ->default('pending')
                  ->comment('Current status of modification');
            
            // Optional notes
            $table->text('description')
                  ->nullable()
                  ->comment('Additional notes about the modification');
            
            // Timestamps
            $table->timestamps();
            
            // Soft deletes
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('production_date', 'idx_production_date');
            $table->index('status', 'idx_status');
            $table->index('asset_model_id', 'idx_asset_model');
            $table->index(['production_date', 'status'], 'idx_production_status');
            
            // Composite index for H-7 warning queries
            $table->index(['status', 'production_date'], 'idx_warning_query');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mold_modifications');
    }
};
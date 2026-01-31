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
        Schema::table('users', function (Blueprint $table) {
            // Make email nullable
            $table->string('email')->nullable()->change();
            
            // Add phone_number (nullable)
            $table->string('phone_number')->nullable()->after('username');
            
            // Add is_active (boolean, default true)
            $table->boolean('is_active')->default(true)->after('phone_number');
            
            // Add indexes for performance
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['is_active']);
            
            // Drop columns
            $table->dropColumn(['phone_number', 'is_active']);
            
            // Make email required again
            $table->string('email')->nullable(false)->change();
        });
    }
};

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
            // Add username column (unique)
            $table->string('username')->unique()->after('name');
            
            // Add soft deletes column
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop username column
            $table->dropColumn('username');
            
            // Drop soft deletes column
            $table->dropSoftDeletes();
        });
    }
};

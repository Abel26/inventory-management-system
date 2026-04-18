<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_logs', function (Blueprint $table) {
            $table->text('admin_comments')->nullable()->after('notes');
            $table->timestamp('admin_commented_at')->nullable()->after('admin_comments');
            $table->unsignedBigInteger('admin_commented_by')->nullable()->after('admin_commented_at');
            
            $table->foreign('admin_commented_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('work_logs', function (Blueprint $table) {
            $table->dropForeign(['admin_commented_by']);
            $table->dropColumn(['admin_comments', 'admin_commented_at', 'admin_commented_by']);
        });
    }
};

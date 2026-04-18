<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_logs', function (Blueprint $table) {
            $table->string('attachment_path', 255)->nullable()->after('admin_commented_by');
            $table->string('attachment_name', 255)->nullable()->after('attachment_path');
            $table->integer('attachment_size')->nullable()->after('attachment_name');
            $table->string('attachment_mime_type', 100)->nullable()->after('attachment_size');
        });
    }

    public function down(): void
    {
        Schema::table('work_logs', function (Blueprint $table) {
            $table->dropColumn(['attachment_path', 'attachment_name', 'attachment_size', 'attachment_mime_type']);
        });
    }
};

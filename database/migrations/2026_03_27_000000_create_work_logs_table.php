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
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->string('work_code', 50)->unique()->comment('Kode unik pekerjaan (WP-YYYYMMDD-XXXX)');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('ID pegawai yang menginput');
            $table->text('description')->comment('Deskripsi pekerjaan');
            $table->date('work_date')->comment('Tanggal kerja (untuk grouping berdasarkan tanggal)');
            $table->time('start_time')->comment('Jam mulai kerja (HH:MM:SS)');
            $table->time('end_time')->nullable()->comment('Jam selesai kerja (HH:MM:SS)');
            $table->integer('break_duration')->default(0)->comment('Durasi istirahat dalam menit');
            $table->integer('total_work_minutes')->nullable()->comment('Total jam kerja dalam menit (dihitung otomatis: end_time - start_time - break_duration)');
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'On Hold', 'Cancelled'])->default('Pending')->comment('Status pekerjaan');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium')->comment('Prioritas pekerjaan');
            $table->enum('work_type', ['Regular', 'Overtime', 'Remote', 'On-Site'])->default('Regular')->comment('Tipe pekerjaan');
            $table->integer('completion_percentage')->default(0)->comment('Persentase penyelesaian (0-100)');
            $table->text('notes')->nullable()->comment('Catatan tambahan');
            $table->foreignId('location_id')->nullable()->constrained('gedungs')->onDelete('set null')->comment('ID lokasi/gedung (opsional)');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('user_id');
            $table->index('status');
            $table->index('work_date');
            $table->index('start_time');
            $table->index('end_time');
            $table->index('created_at');
            $table->index('work_code');
            $table->index(['user_id', 'work_date'])->name('idx_user_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};

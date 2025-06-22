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
        Schema::create('ncr_fix', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('draft_id');
            $table->unsignedBigInteger('user_id');
            $table->string('writer', 100);
            $table->date('tanggal_shift_kerja');
            $table->string('shift_kerja', 8);
            $table->string('nama_hs_officer_1', 100)->nullable();
            $table->string('nama_hs_officer_2', 100)->nullable();
            $table->date('tanggal_audit');
            $table->string('nama_auditee', 255)->nullable();
            $table->string('perusahaan', 255)->nullable();
            $table->string('nama_bagian', 255)->nullable();
            $table->string('element_referensi_ncr', 255)->nullable();
            $table->string('kategori_ketidaksesuaian', 255)->nullable();
            $table->string('deskripsi_ketidaksesuaian', 255)->nullable();
            $table->string('status', 30)->default('Nothing');
            $table->string('status_note', 255)->nullable();
            $table->string('status_ncr', 7)->default('Open');
            $table->string('durasi_ncr',20)->nullable();
            $table->date('estimasi')->nullable();
            $table->string('tindak_lanjut', 255)->nullable();
            $table->binary('foto')->nullable();
            $table->binary('foto_closed')->nullable();
            $table->binary('waktu_closed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ncr_fix');
    }
};

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
            $table->string('writer');
            $table->date('tanggal_shift_kerja');
            $table->text('shift_kerja');
            $table->text('nama_hs_officer_1')->nullable();
            $table->text('nama_hs_officer_2')->nullable();
            $table->date('tanggal_audit');
            $table->text('nama_auditee')->nullable();
            $table->text('perusahaan')->nullable();
            $table->text('nama_bagian')->nullable();
            $table->text('element_referensi_ncr')->nullable();
            $table->text('kategori_ketidaksesuaian')->nullable();
            $table->text('deskripsi_ketidaksesuaian')->nullable();
            $table->string('status', 30)->default('Nothing');
            $table->text('status_note')->nullable();
            $table->string('status_ncr', 50)->default('Open');
            $table->text('durasi_ncr')->nullable();
            $table->date('estimasi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->binary('foto')->nullable();
            $table->binary('foto_closed')->nullable();
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

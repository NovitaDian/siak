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
        Schema::create('ppe_draft', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('writer');
            $table->date('tanggal_shift_kerja');
            $table->string('shift_kerja', 50);
            $table->unsignedBigInteger('hse_inspector_id');
            $table->string('nama_hse_inspector', 100);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('zona_pengawasan', 100);
            $table->string('lokasi_observasi', 100);
            $table->integer('jumlah_patuh_apd_karyawan')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_helm_karyawan')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_sepatu_karyawan')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_pelindung_mata_karyawan')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_safety_harness_karyawan')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_apd_lainnya_karyawan')->nullable()->default(0);
            $table->string('keterangan_tidak_patuh')->nullable();
            $table->integer('jumlah_patuh_apd_kontraktor')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_helm_kontraktor')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_sepatu_kontraktor')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_pelindung_mata_kontraktor')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_safety_harness_kontraktor')->nullable()->default(0);
            $table->integer('jumlah_tidak_patuh_apd_lainnya_kontraktor')->nullable()->default(0);
            $table->string('status_ppe', 100)->nullable();
            $table->string('status', 30)->default('Nothing');
            $table->timestamps();
            $table->foreign('hse_inspector_id')->references('id')->on('hse_inspector')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppe_draft');
    }
};

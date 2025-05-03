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
        Schema::create('ncr', function (Blueprint $table) {
            $table->id(); 
            $table->string('writer', 255);
            $table->date('tanggal_shift_kerja');
            $table->text('shift_kerja');
            $table->text('nama_hs_officer_1');
            $table->text('nama_hs_officer_2');
            $table->date('tanggal_audit');
            $table->text('nama_auditee');
            $table->text('perusahaan');
            $table->text('nama_bagian')->nullable();
            $table->text('element_referensi_ncr');
            $table->text('kategori_ketidaksesuaian');
            $table->text('deskripsi_ketidaksesuaian');
            $table->text('status_note')->nullable();
            $table->string('status_ncr', 50)->default('Open');
            $table->integer('durasi_ncr')->nullable();
            $table->timestamps(); 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ncr');
    }
};

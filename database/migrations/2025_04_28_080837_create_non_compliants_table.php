<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonCompliantsTable extends Migration
{
    public function up(): void
    {
        Schema::create('non_compliants', function (Blueprint $table) {
            $table->id();
            $table->Integer('id_ppe');
            $table->string('nama_hse_inspector', 100);
            $table->string('shift_kerja', 100);
            $table->string('jam_pengawasan', 100);
            $table->string('zona_pengawasan', 100);
            $table->string('lokasi_observasi', 100);
            $table->string('tipe_observasi', 100);
            $table->text('deskripsi_ketidaksesuaian');
            $table->string('nama_pelanggar', 100);
            $table->string('perusahaan', 100);
            $table->string('nama_bagian', 100);
            $table->text('tindakan')->nullable();
            $table->string('writter', 100);
            $table->timestamps();

            $table->foreign('id_ppe')->references('id')->on('ppe_fix')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('non_compliants');
    }
}

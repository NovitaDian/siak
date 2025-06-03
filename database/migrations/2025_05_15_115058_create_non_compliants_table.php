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
            $table->unsignedBigInteger('id_ppe');
            $table->string('nama_hse_inspector', 100);
            $table->string('shift_kerja', 100);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('zona_pengawasan', 100);
            $table->string('lokasi_observasi', 100);
            $table->string('tipe_observasi', 100);
            $table->text('deskripsi_ketidaksesuaian');
            $table->string('nama_pelanggar', 100);
            $table->string('perusahaan', 100);
            $table->string('nama_bagian', 100);
            $table->text('tindakan')->nullable();
            $table->string('writer', 100);
            $table->string('status', 30)->default('Nothing');
            $table->binary('foto', 100);
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_ppe')->references('id')->on('ppe_fix')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('non_compliants');
    }
}

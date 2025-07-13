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
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nama_alat_id');
            $table->string('nomor');
            $table->timestamp('waktu_inspeksi')->nullable();
            $table->integer('durasi_inspeksi')->comment('Durasi dalam hari');
            $table->string('status')->nullable();
            $table->timestamps();
            $table->foreign('nama_alat_id')->references('id')->on('alats')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};

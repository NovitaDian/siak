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
        Schema::create('daily_draft', function (Blueprint $table) {
            $table->id();
            $table->string('writer', 255);
            $table->date('tanggal_shift_kerja');
            $table->string('shift_kerja');
            $table->unsignedBigInteger('hse_inspector_id');
            $table->string('nama_hse_inspector');
            $table->text('rincian_laporan');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('hse_inspector_id')->references('id')->on('hse_inspector')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_draft');
    }
};

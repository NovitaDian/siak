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
        Schema::create('daily_fix', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('draft_id');
            $table->unsignedBigInteger('user_id');
            $table->string('writer', 100);
            $table->date('tanggal_shift_kerja');
            $table->string('shift_kerja');
            $table->unsignedBigInteger('hse_inspector_id');
            $table->string('nama_hse_inspector');
            $table->text('rincian_laporan');
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
        Schema::dropIfExists('daily_fix');
    }
};

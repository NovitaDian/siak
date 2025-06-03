<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tool_inspections_draft', function (Blueprint $table) {
            $table->id();
            $table->string('writer', 255);
            $table->unsignedBigInteger('alat_id');
            $table->string('nama_alat');
            $table->unsignedBigInteger('hse_inspector_id');
            $table->string('hse_inspector');
            $table->date('tanggal_pemeriksaan');
            $table->binary('foto');
            $table->enum('status_pemeriksaan', ['Layak operasi', 'Layak operasi dengan catatan', 'Tidak layak operasi']);
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             $table->foreign('alat_id')->references('id')->on('alats')->onDelete('cascade');
            $table->foreign('hse_inspector_id')->references('id')->on('hse_inspector')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_inspections_draft');
    }
};

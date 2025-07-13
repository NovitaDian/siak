<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bagian_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perusahaan_id')->nullable();
            $table->string('nama_bagian');
            $table->timestamps();
            $table->foreign('perusahaan_id')->references('id')->on('perusahaan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bagian_perusahaan');
    }
};

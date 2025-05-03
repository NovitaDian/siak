<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bagian_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('perusahaan_code');
            $table->string('perusahaan_name');
            $table->string('nama_bagian');
            $table->timestamps();
            $table->foreign('perusahaan_code')->references('perusahaan_code')->on('perusahaan')->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('bagian_perusahaan');
    }
};

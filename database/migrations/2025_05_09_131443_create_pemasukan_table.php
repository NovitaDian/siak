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
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->date('tanggal');
            $table->integer('quantity');
            $table->string('unit', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key constraint (jika relasi ke tabel `barang`)
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan');
    }
};

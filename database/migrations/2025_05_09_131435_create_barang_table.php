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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('material_code', 50);
            $table->unsignedBigInteger('material_group_id')->nullable();
            $table->text('description')->nullable();
            $table->binary('image')->nullable(); // Untuk tipe BLOB
            $table->string('material_type', 50)->nullable();
            $table->text('remark')->nullable();
            $table->integer('quantity')->default(0);
            $table->unsignedBigInteger('unit_id');
            $table->timestamps();
            $table->foreign('unit_id')->references('id')->on('unit')->onDelete('cascade');
            $table->foreign('material_group_id')->references('id')->on('material_group')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};

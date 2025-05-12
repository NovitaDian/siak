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
            $table->string('material_group', 50);
            $table->text('description')->nullable();
            $table->binary('image')->nullable(); // Untuk tipe BLOB
            $table->string('material_type', 50)->nullable();
            $table->text('remark')->nullable();
            $table->integer('quantity')->default(0);
            $table->unsignedBigInteger('unit_id');
            $table->string('unit', 20)->nullable();
            $table->timestamps();
            $table->foreign('material_group_id')->references('id')->on('material_group')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('unit')->onDelete('cascade');
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

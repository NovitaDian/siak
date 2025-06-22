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
        Schema::create('budget', function (Blueprint $table) {
            $table->id();
            $table->string('internal_order', 255)->nullable();
            $table->string('gl_code', 255);
            $table->string('gl_name', 255);
            $table->decimal('setahun_total', 15, 2)->nullable();
            $table->string('kategori', 255);
            $table->string('year', 100);
            $table->timestamps();
            $table->string('is_main', 5);
            $table->foreign('gl_code')->references('gl_code')->on('gl_account')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget');
    }
};

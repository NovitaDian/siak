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
        Schema::create('budget_fix', function (Blueprint $table) {
            $table->id();
           $table->string('gl_code', 255);
            $table->string('internal_order', 255);
            $table->text('description')->nullable();
            $table->decimal('bg_approve', 15, 2)->nullable();
            $table->decimal('usage', 15, 2)->nullable();
            $table->decimal('percentage_usage', 5, 2)->default(0);
            $table->integer('year');
            $table->string('kategori', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_fix');
    }
};

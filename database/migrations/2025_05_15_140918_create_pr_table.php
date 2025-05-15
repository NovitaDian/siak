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
        Schema::create('pr', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->date('pr_date')->nullable();
            $table->string('pr_no', 50)->unique();
            $table->string('purchase_for', 100)->nullable();
            $table->string('io_assetcode', 100)->nullable();
            $table->string('material', 50)->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit', 20)->nullable();
            $table->decimal('valuation_price', 15, 2)->nullable();
            $table->string('gl_code', 50)->nullable();
            $table->string('gl_name', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr');
    }
};

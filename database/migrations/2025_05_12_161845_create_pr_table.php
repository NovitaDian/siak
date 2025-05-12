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
            $table->string('plant', 50)->nullable();
            $table->string('pr_no', 50)->nullable();
            $table->string('pr_category', 50)->nullable();
            $table->string('account_assignment', 50)->nullable();
            $table->string('item_category', 50)->nullable();
            $table->string('purchase_for', 100)->nullable();
            $table->string('material_code', 50)->nullable();
            $table->string('short_text', 255)->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit', 20)->nullable();
            $table->decimal('valuation_price', 15, 2)->nullable();
            $table->string('gl_account', 50)->nullable();
            $table->string('cost_center', 50)->nullable();
            $table->string('matl_group', 50)->nullable();
            $table->string('purchasing_group', 50)->nullable();
            $table->timestamps();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->string('io_assetcode', 50);
            $table->foreign('io_assetcode')->references('internal_order')->on('budget')->onDelete('cascade');
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

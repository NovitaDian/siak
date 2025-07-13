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
        Schema::create('ncr_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_ncr_id'); // Foreign key, bisa dihubungkan ke tabel lain
            $table->string('type', 6);
            $table->text('reason');
            $table->string('status', 8)->default('Pending');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('sent_ncr_id')->references('id')->on('ncr_fix')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ncr_request');
    }
};

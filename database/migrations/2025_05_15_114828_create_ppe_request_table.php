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
        Schema::create('ppe_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_ppe_id');
            $table->unsignedBigInteger('user_id');
            $table->string('type', 6);
            $table->text('reason');
            $table->string('status', 8)->default('Pending');
            $table->timestamps();
            $table->foreign('sent_ppe_id')->references('id')->on('ppe_fix')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppe_request');
    }
};

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
        Schema::create('daily_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_daily_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nama_pengirim', 100);
            $table->string('type', 6);
            $table->text('reason');
            $table->string('status', 7)->default('Pending');
            $table->timestamps();
            $table->foreign('sent_daily_id')->references('id')->on('daily_fix')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_requests');
    }
};

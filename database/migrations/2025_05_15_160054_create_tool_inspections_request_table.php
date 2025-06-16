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
        Schema::create('tool_inspections_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_tool_id');
            $table->string('nama_pengirim', 100);
            $table->string('type', 6);
            $table->text('reason');
            $table->string('status', 7)->default('Pending');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('sent_tool_id')->references('id')->on('tool_inspections_fix')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_inspections_request');
    }
};

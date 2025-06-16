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
        Schema::create('incident_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_incident_id'); // Foreign key, bisa dihubungkan ke tabel lain
            $table->string('nama_pengirim', 100);
            $table->string('type', 6);
            $table->text('reason');
            $table->string('status', 7)->default('Pending');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('sent_incident_id')->references('id')->on('incidents_fix')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_requests');
    }
};

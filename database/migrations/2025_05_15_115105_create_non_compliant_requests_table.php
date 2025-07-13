<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('non_compliant_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_non_compliant_id');
            $table->string('type', 100);
            $table->text('reason');
            $table->string('status', 8)->default('Pending');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Foreign key ke tabel non_compliants
            $table->foreign('sent_non_compliant_id')
                ->references('id')
                ->on('non_compliants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('non_compliant_requests');
    }
};

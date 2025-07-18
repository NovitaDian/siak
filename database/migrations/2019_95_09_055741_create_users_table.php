<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
           $table->id(); 
            $table->string('name',100);
            $table->string('role', 100);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone', 13)->nullable();
            $table->string('location')->nullable();
            $table->string('about_me')->nullable();
            $table->rememberToken(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

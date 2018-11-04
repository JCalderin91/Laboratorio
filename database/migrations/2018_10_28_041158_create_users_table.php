<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

            $table->string('id',8)->primary();
            
            $table->string('first_name',128);
            $table->string('last_name',128);
            $table->enum('gender', ['F', 'M']);
            $table->string('password');
            $table->string('avatar');
            $table->unsignedInteger('role_id');
            $table->enum('status', ['ACTIVE', 'INACTIVE']);
            $table->rememberToken();
            $table->timestamps();    
            $table->foreign('role_id')->references('id')->on('roles');  
            
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

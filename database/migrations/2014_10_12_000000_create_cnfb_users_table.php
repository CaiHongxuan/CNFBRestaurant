<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCnfbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnfb_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname', 20);
            $table->string('password');
            $table->string('email')->unique()->nullable();
            $table->string('description', 50)->nullable();
            $table->string('head_img_url')->nullable();
            $table->tinyInteger('display')->nullable();
            $table->tinyInteger('hide')->nullable();
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
        Schema::drop('users');
    }
}

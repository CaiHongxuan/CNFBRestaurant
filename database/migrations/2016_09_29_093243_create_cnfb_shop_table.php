<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCnfbShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnfb_shop', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            $table->string('description', 50);
            $table->string('media_id', 20);
            $table->string('address');
            $table->string('tel', 15);
            $table->string('mobile', 11);
            $table->string('qrcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

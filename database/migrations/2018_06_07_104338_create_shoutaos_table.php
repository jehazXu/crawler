<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoutaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoutaos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nick')->comment('掌柜名');
            $table->string('title')->comment('天猫完整产品名');
            $table->string('key')->comment('搜索关键词');
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
        Schema::dropIfExists('shoutaos');
    }
}

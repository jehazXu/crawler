<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoutaorankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoutaorankings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shoutao_id')->unsigned()->comment('手淘产品ID外键');
            $table->foreign('shoutao_id')->references('id')->on('shoutaos')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('ranking')->comment('排名');
            $table->date('date')->comment('获取日期');
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
        Schema::dropIfExists('shoutaorankings');
    }
}

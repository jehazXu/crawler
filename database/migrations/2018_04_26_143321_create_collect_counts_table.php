<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collect_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tproduct_id')->unsigned()->comment('天猫产品ID外键');
            $table->foreign('tproduct_id')->references('id')->on('tmall_products')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('collect_count')->comment('收藏数');
            $table->date('count_date')->comment('获取日期');
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
        Schema::dropIfExists('collect_counts');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalsisInfosTable extends Migration
{
    /**
     * Run the migrations.
     *  分析详情表
     * @return void
     */
    public function up()
    {
        Schema::create('analsis_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_analysis_id')->comment('产品分析id');
            $table->integer('day_flow')->nullable()->comment('当日流量');
            $table->integer('day_hit_count')->nullable()->comment('当日命中笔数');
            $table->double('conversion_rate',10,2)->nullable()->comment('转化率(%)');
            $table->string('m_ranking')->default('')->comment('M端排名');
            $table->index('product_analysis_id');
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
        Schema::dropIfExists('analsis_infos');
    }
}

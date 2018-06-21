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
            $table->string('keyword')->comment('关键字');
            $table->integer('uv')->nullable()->comment('当日流量(访客数)');
            $table->integer('pv_value')->nullable()->comment('浏览量');
            $table->double('pv_ratio')->nullable()->comment('浏览量占比');
            $table->integer('bounce_self_uv')->nullable()->comment('店内跳转人数');
            $table->integer('bounce_uv')->nullable()->comment('跳出本店人数');
            $table->integer('clt_cnt')->nullable()->comment('收藏人数');
            $table->integer('cart_byr_cnt')->nullable()->comment('加购人数');
            $table->integer('crt_byr_cnt')->nullable()->comment('当日命中笔数(下单买家数)');
            $table->double('crt_rate')->nullable()->comment('下单转化率');
            $table->integer('pay_itm_cnt')->nullable()->comment('支付件数');
            $table->integer('pay_byr_cnt')->nullable()->comment('支付买家数');
            $table->double('pay_rate')->nullable()->comment('支付转化率');
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

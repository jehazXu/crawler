<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *  产品分析表
     * @return void
     */
    public function up()
    {
        Schema::create('product_analysis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('产品名称');
            $table->string('keyword')->comment('关键字');
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
        Schema::dropIfExists('product_analyses');
    }
}

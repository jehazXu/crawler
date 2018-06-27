<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnToProductAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_analysis', function (Blueprint $table) {
            $table->string('keyword')->after('skuid')->comment('关键词');
            $table->date('str_time')->after('keyword')->comment('开始日期');
            $table->date('end_time')->after('str_time')->comment('结束日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_analysis', function (Blueprint $table) {
            $table->dropColumn('keyword');
            $table->dropColumn('str_time');
            $table->dropColumn('end_time');

        });
    }
}

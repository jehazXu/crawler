<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnToAnalsisInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analsis_infos', function (Blueprint $table) {
            $table->date('day')->after('pay_rate')->comment('数据记录的日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analsis_infos', function (Blueprint $table) {
            $table->dropColumn('day');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmallProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmall_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product')->comment('天猫产品名');
            $table->string('skuid')->comment('天猫产品id');
            $table->text('url')->comment('天猫产品链接');
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
        Schema::dropIfExists('tmall_products');
    }
}

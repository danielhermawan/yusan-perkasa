<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
           
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->integer('unit_id')->unsigned();
            $table->string('name');
            $table->integer('quantity');
            $table->integer('min_quantity');
            $table->integer('max_quantity');
            $table->integer('max_purchase_price');
            $table->integer('min_sales_price');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('type_id')->references('id')->on('product_types');
            $table->foreign('unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('units');
        Schema::dropIfExists('product_types');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone', 20);
            $table->string('faks', 20);
            $table->string('email')->unique();
            $table->string('address');
            $table->string('zip_code', 20);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_supplier', function (Blueprint $table) {
            $table->integer('supplier_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->double('price', 15, 8);
            $table->softDeletes();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->primary(['supplier_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_supplier');
        Schema::dropIfExists('suppliers');
    }
}

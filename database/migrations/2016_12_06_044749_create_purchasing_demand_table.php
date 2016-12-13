<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingDemandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_demands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::create('product_purchase_demand', function (Blueprint $table) {
            $table->integer('purchase_demand_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('quantity');
            $table->softDeletes();
            $table->foreign('purchase_demand_id')->references('id')->on('purchase_demands')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->primary(['purchase_demand_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_purchasing_demand');
        Schema::dropIfExists('purchasing_demands');
    }
}

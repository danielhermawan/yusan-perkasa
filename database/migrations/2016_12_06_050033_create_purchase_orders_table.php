<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->integer('supplier_id')->unsigned();
            $table->integer('purchase_demand_id')->unsigned();
            $table->date('due_date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('purchase_demand_id')->references('id')->on('purchase_demands')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::create('product_purchase_orders', function (Blueprint $table) {
            $table->integer('purchase_order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('quantity');
            $table->double('price', 15, 8);
            $table->enum('status', ['0', '1', '2']);
            $table->softDeletes();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->primary(['purchase_order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_purchase_orders');
        Schema::dropIfExists('purchase_orders');
    }
}

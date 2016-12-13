<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->integer('purchase_order_id')->unsigned();
            $table->date('receiving_date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::create('product_product_receipt', function (Blueprint $table) {
            $table->integer('product_receipt_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('quantity');
            $table->enum('status', ['0', '1', '2']);
            $table->softDeletes();
            $table->foreign('product_receipt_id')->references('id')->on('product_receipts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->primary(['product_receipt_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_product_receipt');
        Schema::dropIfExists('product_receipts');
    }
}

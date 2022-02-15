<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('session_id', 255);
			$table->integer('product_id')->default('0');
			$table->integer('quantity')->default('0');
			$table->decimal('item_price',10,2)->default('0.00');
			$table->decimal('item_total',10,2)->default('0.00');
			$table->decimal('cgst',10,2)->default('0.00');
			$table->decimal('sgst',10,2)->default('0.00');
			$table->decimal('igst',10,2)->default('0.00');
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
        Schema::dropIfExists('temp_cart_items');
    }
}

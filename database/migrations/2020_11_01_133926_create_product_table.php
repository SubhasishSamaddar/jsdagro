<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prod_name', 200);
            $table->integer('category_id')->default('0');
            $table->decimal('price',10,2)->default('0.00');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('product_image', 255)->default('product/product.png');
            $table->mediumText('description')->nullable();
            $table->mediumText('specification')->nullable();
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
        Schema::dropIfExists('products');
    }
}

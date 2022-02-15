<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->enum('inventory_in_out',['In', 'Out'])->default('In');
			$table->integer('user_id')->default('0');
			$table->integer('package_location_id')->default('0');
			$table->string('prod_name', 255);
			$table->integer('category_id')->default('0');
			$table->decimal('total_weight',12,6)->default('0.00');
			$table->decimal('purchased_price',10,2)->default('0.00');
			$table->decimal('selling_price',10,2)->default('0.00');
			$table->decimal('cgst',10,2)->default('0.00');
			$table->decimal('sgst',10,2)->default('0.00');
			$table->decimal('igst',10,2)->default('0.00');
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
        Schema::dropIfExists('package_inventories');
    }
}

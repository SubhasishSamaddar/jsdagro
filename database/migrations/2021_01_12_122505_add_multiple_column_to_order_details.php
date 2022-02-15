<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnToOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->string('product_name', 200)->after('product_id');
			$table->string('product_image', 255)->default('product/product.png')->after('product_id');
            $table->string('sku')->nullable()->after('product_image');
			$table->decimal('weight_per_pkt',10,2)->default(0.00)->after('sku');
			$table->string('weight_unit')->nullable()->after('weight_per_pkt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            //
        });
    }
}

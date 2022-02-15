<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('category_id');
            $table->integer('swadesh_hut_id')->nullable()->after('sku');
            $table->decimal('weight_per_pkt',10,2)->default(0.00)->after('swadesh_hut_id');
            $table->decimal('cgst',10,2)->default(0.00)->after('price');
            $table->decimal('sgst',10,2)->default(0.00)->after('cgst');
            $table->decimal('igst',10,2)->default(0.00)->after('sgst');
            $table->integer('available_qty')->default(0)->after('specification');
            $table->integer('ordered_qty')->default(0)->after('available_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}

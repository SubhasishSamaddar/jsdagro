<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnToOrderMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_master', function (Blueprint $table) {
			
			$table->string('billing_name', 255);
			$table->string('billing_email', 255);
			$table->string('billing_phone', 255);
			$table->mediumText('billing_street')->nullable();
			$table->string('billing_city', 255);
			$table->string('billing_pincode', 10);
			$table->string('billing_state', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_master', function (Blueprint $table) {
            //
        });
    }
}

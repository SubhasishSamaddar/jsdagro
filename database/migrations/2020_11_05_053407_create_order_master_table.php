<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number', 255);
			$table->integer('user_id')->default('0');    
			$table->decimal('total_amount',10,2)->default('0.00');
			$table->integer('swadesh_hut_id')->default('0');
			$table->dateTime('order_date', 0);
			$table->enum('order_status',['Ordered', 'Packed', 'Delivered'])->default('Ordered');
			$table->enum('payment_status',['Paid', 'Unpaid'])->default('Paid');
			$table->enum('payment_mode',['Online', 'COD'])->default('COD');  
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
        Schema::dropIfExists('order_master');
    }
}

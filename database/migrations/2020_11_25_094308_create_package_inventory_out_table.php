<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageInventoryOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_inventory_out', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('package_inventory_id')->nullable();
            $table->integer('package_location_id')->nullable();
            $table->integer('swadesh_hut_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('inv_out_qty')->nullable();
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
        Schema::dropIfExists('package_inventory_out');
    }
}

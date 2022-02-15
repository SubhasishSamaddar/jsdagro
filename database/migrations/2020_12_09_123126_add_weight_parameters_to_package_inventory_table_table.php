<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeightParametersToPackageInventoryTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_inventories', function (Blueprint $table) {
            $table->float('weight_per_packet', 8, 2)->after('category_id')->default(1);
            $table->string('weight_unit')->after('weight_per_packet')->default('kilogram');
            $table->integer('no_of_packet')->after('weight_unit')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_inventories', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('location_name', 255);
			$table->mediumText('cover_area')->nullable();
			$table->enum('status',['Active', 'Inventory'])->default('Active');
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
        Schema::dropIfExists('package_locations');
    }
}

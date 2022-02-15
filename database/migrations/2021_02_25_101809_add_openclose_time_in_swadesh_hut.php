<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpencloseTimeInSwadeshHut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('swadesh_huts', function (Blueprint $table) {
            $table->time('open_time', 0)->nullable();
            $table->time('close_time', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('swadesh_hut', function (Blueprint $table) {
            //
        });
    }
}

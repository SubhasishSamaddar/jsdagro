<?php



use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;



class AddColumnToBannerTable extends Migration

{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()

    {

        Schema::table('banners', function (Blueprint $table) {

            $table->string('banner_image')->nullable();

            $table->string('banner_image_text')->nullable();

            $table->enum('banner_image_type', ['normal', 'small'])->default('normal');

        });

    }



    /**
     * Reverse the migrations.

     *

     * @return void

     */

    public function down()

    {

        Schema::table('banners', function (Blueprint $table) {

            //

        });

    }

}

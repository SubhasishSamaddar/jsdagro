<?php



use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;



class CreateBannersTable extends Migration

{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()
    {

       Schema::create('banners', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('banner_images');

            $table->string('small_banner_images');

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

        Schema::dropIfExists('banners');

    }

}

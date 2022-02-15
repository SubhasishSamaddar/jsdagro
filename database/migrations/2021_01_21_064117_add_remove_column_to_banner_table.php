<?php



use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;



class AddRemoveColumnToBannerTable extends Migration

{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()

    {

        Schema::table('banners', function (Blueprint $table) {

           $table->dropColumn('small_banner_images');

            $table->dropColumn('banner_images');

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

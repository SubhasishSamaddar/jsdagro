<?php



use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;



class AddHomePageShowInCategoryTable extends Migration

{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()

    {

        Schema::table('categories', function (Blueprint $table) {

            $table->enum('show_home_page', ['Show', 'Hide'])->default('Hide');

        });

   }



    /**

     * Reverse the migrations.

     *

     * @return void

     */

    public function down()

    {

        Schema::table('category', function (Blueprint $table) {

            //

        });

    }

}

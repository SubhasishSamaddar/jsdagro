<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 150);      
            $table->string('aliases', 150)->nullable();               
            $table->string('sender_name', 150)->nullable();   
            $table->string('sender_email', 150)->nullable();  
            $table->string('cc_email', 150)->nullable();
            $table->string('bcc_email', 150)->nullable(); 
            $table->longText('content')->nullable(); 
            $table->enum('status', ['Active', 'Inactive'])->default('Inactive'); 
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
        Schema::dropIfExists('email_templates');
    }
}

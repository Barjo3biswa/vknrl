<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationEditedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE TABLE application_editeds LIKE applications');
        // Schema::create('application_editeds', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->timestamps();
        // });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->integer("application_id")->unsigned()->nullable();
            $table->integer("edited_by_id")->unsigned()->nullable();
            $table->string("edited_by")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_editeds');
    }
}

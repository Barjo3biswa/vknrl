<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmissionDateTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_date_times', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime("opening_date_time");
            $table->dateTime("closing_date_time");
            $table->integer("session_id")->unsigned()->nullable();
            $table->text("opening_message")->nullable();
            $table->text("closing_message")->nullable();
            $table->integer("added_by")->unsigned()->nullable();
            $table->integer("deleted_by")->unsigned()->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('admission_date_times');
    }
}

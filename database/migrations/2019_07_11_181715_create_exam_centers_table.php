<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_centers', function (Blueprint $table) {
            $table->increments('id');
            $table->string("center_code")->nullable();
            $table->string("center_name")->nullable();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->string("pin")->nullable();
            $table->bigInteger("created_by")->unsinged()->nullable();
            $table->bigInteger("deleted_by")->unsinged()->nullable();
            $table->softDeletes();
            $table->timestamps();
            // $table->foreign('created_by')->references('id')->on('admins');
            // $table->foreign('deleted_by')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_centers');
    }
}

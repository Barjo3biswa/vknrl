<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmitCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admit_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('application_id')->unsigned();
            $table->bigInteger('exam_center_id')->unsigned();
            $table->string('exam_time')->nullable();
            $table->date('exam_date')->nullable();
            $table->bigInteger('generated_by')->unsigned()->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
            // $table->foreign('created_by')->references('id')->on('admins');
            // $table->foreign('application_id')->references('id')->on('applications');
            // $table->foreign('exam_center_id')->references('id')->on('exam_centers');
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
        Schema::dropIfExists('admit_cards');
    }
}

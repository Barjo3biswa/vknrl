<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_remarks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("application_id");
            $table->string("remark");
            $table->unsignedInteger("remark_added_id");
            $table->string("remark_added_by");
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("application_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_remarks');
    }
}

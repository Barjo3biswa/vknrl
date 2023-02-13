<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminCardPublishField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admit_cards', function (Blueprint $table) {
            $table->tinyInteger("publish")->unsigned()->default(0)->after("exam_date")->comment("1 publish, 0 draft");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admit_cards', function (Blueprint $table) {
            $table->dropColumn(["publish"]);
        });
    }
}

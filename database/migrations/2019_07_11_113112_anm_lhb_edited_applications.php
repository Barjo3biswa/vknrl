<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AnmLhbEditedApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->tinyInteger('anm_or_lhv')->unsigned()->default(0)->after("ncl_valid_upto");
            $table->string('anm_or_lhv_registration', 100)->nullable()->default('NA')->after("anm_or_lhv");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn(["anm_or_lhv", "anm_or_lhv_registration"]);
        });
    }
}

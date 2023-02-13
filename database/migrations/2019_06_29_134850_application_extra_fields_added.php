<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplicationExtraFieldsAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedTinyInteger("bpl")->default(0)->nullable()->after("person_with_disablity");
            $table->unsignedTinyInteger("same_address")->default(0)->nullable()->after("permanent_pin");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(["bpl", "same_address"]);
        });
    }
}

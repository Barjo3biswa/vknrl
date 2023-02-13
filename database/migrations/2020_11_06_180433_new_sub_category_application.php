<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewSubCategoryApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('sub_cat', 100)->nullable()->after("caste_id");
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->string('sub_cat', 100)->nullable()->after("caste_id");
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
            $table->dropColumn('sub_cat');
        });
        Schema::table('application_editeds', function (Blueprint $table) {
            $table->dropColumn('sub_cat');
        });
    }
}

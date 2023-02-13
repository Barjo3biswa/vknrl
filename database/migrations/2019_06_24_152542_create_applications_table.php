<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string("fullname")->nullable();
            $table->enum('gender', ['Male', 'Female', "Transgender"])->nullable();
            $table->enum('marital_status', ['Married', "Unmarried"])->nullable();
            $table->string("religion")->nullable();
            $table->string("nationality")->nullable();
            $table->unsignedInteger("caste_id")->nullable();
            $table->unsignedInteger("session_id")->nullable();
            $table->date("dob")->nullable();
            $table->date("ncl_valid_upto")->nullable();

            $table->string("father_name")->nullable();
            $table->string("mother_name")->nullable();

            $table->string("permanent_village_town")->nullable();
            $table->string("permanent_po")->nullable();
            $table->string("permanent_ps")->nullable();
            $table->string("permanent_state")->nullable();
            $table->string("permanent_district")->nullable();
            $table->string("permanent_contact_number")->nullable();

            $table->string("correspondence_village_town")->nullable();
            $table->string("correspondence_po")->nullable();
            $table->string("correspondence_ps")->nullable();
            $table->string("correspondence_state")->nullable();
            $table->string("correspondence_district")->nullable();
            $table->string("correspondence_contact_number")->nullable();

            $table->string("academic_10_stream")->nullable();
            $table->string("academic_10_year")->nullable();
            $table->string("academic_10_board")->nullable();
            $table->string("academic_10_school")->nullable();
            $table->string("academic_10_subject")->nullable();
            $table->string("academic_10_mark")->nullable();
            $table->string("academic_10_percentage")->nullable();

            $table->string("academic_12_stream")->nullable();
            $table->string("academic_12_year")->nullable();
            $table->string("academic_12_board")->nullable();
            $table->string("academic_12_school")->nullable();
            $table->string("academic_12_subject")->nullable();
            $table->string("academic_12_mark")->nullable();
            $table->string("academic_12_percentage")->nullable();

            $table->string("academic_voc_stream")->nullable();
            $table->string("academic_voc_year")->nullable();
            $table->string("academic_voc_board")->nullable();
            $table->string("academic_voc_school")->nullable();
            $table->string("academic_voc_subject")->nullable();
            $table->string("academic_voc_mark")->nullable();
            $table->string("academic_voc_percentage")->nullable();

            $table->string("academic_anm_stream")->nullable();
            $table->string("academic_anm_year")->nullable();
            $table->string("academic_anm_board")->nullable();
            $table->string("academic_anm_school")->nullable();
            $table->string("academic_anm_subject")->nullable();
            $table->string("academic_anm_mark")->nullable();
            $table->string("academic_anm_percentage")->nullable();

            $table->string("person_with_disablity")->nullable();
            $table->string("other_qualification")->nullable();
            $table->string("english_mark_obtain")->nullable();
            $table->tinyInteger("diclaration_accept")->default(0);
            $table->string("status")->default("application_submitted")->nullable();
            $table->string("selected_caste_id")->nullable();
            $table->string("hold_reason")->nullable();
            $table->string("reject_reason")->nullable();
            $table->tinyInteger("form_step")->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("session_id")->references("id")->on('sessions');
        });
        DB::statement("ALTER TABLE applications AUTO_INCREMENT = 1000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}

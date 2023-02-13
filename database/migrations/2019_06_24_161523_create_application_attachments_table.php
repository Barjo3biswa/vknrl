<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("application_id");
            $table->string("doc_name");
            $table->string("file_name");
            $table->string("original_name");
            $table->string("mime_type");
            $table->string("destination_path");
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("application_id")->references("id")->on("applications");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_attachments');
    }
}

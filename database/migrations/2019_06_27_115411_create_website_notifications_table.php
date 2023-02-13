<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title")->nullable();
            $table->string("doc_url")->nullable();
            $table->string("doc_name")->nullable();
            $table->date("notification_date");
            $table->string("notification_type")->nullable();
            $table->string("created_by")->nullable();
            $table->string("created_by_id")->nullable();
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
        Schema::dropIfExists('website_notifications');
    }
}

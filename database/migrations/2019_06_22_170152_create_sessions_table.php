<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
            $table->unsignedInteger("is_active")->default(0);
            $table->unsignedInteger("created_by")->nullable();
            $table->softDeletes();
            $table->timestamps();
            // $table->foreign("created_by")->references("id")->on("users");
            $table->foreign("created_by")->references("id")->on("admins");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}

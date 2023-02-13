<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('roll_number')->nullable();
            $table->string('mobile_no')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string("otp")->nullable();
            $table->unsignedInteger("otp_verified")->default(0);
            $table->unsignedInteger("otp_retry")->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE users AUTO_INCREMENT = 1000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

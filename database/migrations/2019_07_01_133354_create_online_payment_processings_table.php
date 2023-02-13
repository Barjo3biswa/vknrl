<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlinePaymentProcessingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_payment_processings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->unsigned()->nullable();
            $table->integer('student_id')->unsigned()->nullable();
            $table->string("order_id")->nullable();
            $table->string("amount")->nullable();
            $table->string("amount_in_paise")->nullable();
            $table->string("currency")->nullable();
            $table->string("merchant_order_id")->nullable();
            $table->unsignedInteger("payment_done")->default(0);
            $table->unsignedInteger("online_payment_successes_id")->nullable();
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
        Schema::dropIfExists('online_payment_processings');
    }
}

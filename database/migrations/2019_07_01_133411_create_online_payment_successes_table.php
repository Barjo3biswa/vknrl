<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlinePaymentSuccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_payment_successes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->unsigned()->nullable();
            $table->integer('student_id')->unsigned()->nullable();
            $table->string("order_id")->nullable();
            $table->string("amount")->nullable();
            $table->string("amount_in_paise")->nullable();
            $table->string("response_amount")->nullable();
            $table->string("currency")->nullable();
            $table->string("merchant_order_id")->nullable();
            $table->string("payment_id")->nullable();
            $table->string("payment_signature")->nullable();
            $table->text('biller_response')->nullable();
            $table->string("is_error")->default(0);
            $table->string("error_message")->nullable();
            $table->string("biller_status")->nullable();
            $table->unsignedInteger("status")->default(0);
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
        Schema::dropIfExists('online_payment_successes');
    }
}

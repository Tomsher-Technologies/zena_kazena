<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaykuPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payku_payments', function (Blueprint $table) {
            $table->string('transaction_id')->unique('payku_payments_transaction_id_unique');
            $table->date('start');
            $table->date('end');
            $table->string('media');
            $table->string('transaction_key', 255)->nullable();
            $table->string('payment_key', 255)->nullable();
            $table->string('verification_key');
            $table->string('authorization_code');
            $table->unsignedInteger('last_4_digits')->nullable();
            $table->string('installments')->nullable();
            $table->string('card_type')->nullable();
            $table->string('additional_parameters')->nullable();
            $table->string('currency');
            $table->timestamps();
            
            $table->foreign('transaction_id', 'payku_payments_transaction_id_foreign')->references('id')->on('payku_transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payku_payments');
    }
}

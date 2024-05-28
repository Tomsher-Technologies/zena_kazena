<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transfers', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('order_detail_id');
            $table->integer('product_id');
            $table->integer('shop_from');
            $table->integer('shop_to');
            $table->boolean('status')->default(0);
            $table->integer('quantity');
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
        Schema::dropIfExists('order_transfers');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsBitcoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_bitcoins', function (Blueprint $table) {
            $table->id();
            $table->integer('paid_id');
            $table->string('price_cryptocurrency');
            $table->string('hash');
            $table->string('name',50);
            $table->string('baseAsset',50);
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
        Schema::dropIfExists('payments_bitcoins');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsBsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_bs', function (Blueprint $table) {
            $table->id();
            $table->integer('paid_id');
            $table->integer("type"); // 0 transferencia y 1 pago móvil
            $table->string('bank', 100);
            $table->string('idCard', 15);
            $table->string('accountNumber', 50)->nullable();
            $table->string('accountType', 1)->nullable();
            $table->string('phone', 20)->nullable();
            $table->float("total");
            $table->String("date",50);
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
        Schema::dropIfExists('payments_bs');
    }
}

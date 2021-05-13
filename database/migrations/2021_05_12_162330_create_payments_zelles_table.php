<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsZellesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_zelles', function (Blueprint $table) {
            $table->id();
            $table->integer('paid_id');
            $table->string('nameAccount', 50);
            $table->string('idConfirm', 50);
            $table->String("date_created",50);
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
        Schema::dropIfExists('payments_zelles');
    }
}

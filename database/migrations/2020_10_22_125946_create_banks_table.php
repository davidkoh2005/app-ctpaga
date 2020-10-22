<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('coin', 3);
            $table->string('country', 10)->nullable();
            $table->string('accountName', 100);
            $table->string('accountNumber', 50);
            $table->string('idCard', 15)->nullable();
            $table->string('route', 9)->nullable();
            $table->string('swift', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('bankName', 100);
            $table->string('accountType', 1);
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
        Schema::dropIfExists('banks');
    }
}

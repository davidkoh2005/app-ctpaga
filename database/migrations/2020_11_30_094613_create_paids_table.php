<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('commerce_id');
            $table->foreign('commerce_id')->references('id')->on('commerces');
            $table->string("codeUrl",10);
            $table->string("nameClient",50);
            $table->String("total");
            $table->integer("coin");
            $table->string("email");
            $table->string("nameShopping",50)->nullable();
            $table->string("numberShopping", 20)->nullable();
            $table->string("addressShopping")->nullable();
            $table->string("detailsShopping")->nullable();
            $table->integer('shipping_id')->default(0);
            $table->integer("percentage");
            $table->String("nameCompanyPayments", 10);
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
        Schema::dropIfExists('paids');
    }
}

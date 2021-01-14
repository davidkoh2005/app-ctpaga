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
            $table->string("nameShipping",50)->nullable();
            $table->string("numberShipping", 20)->nullable();
            $table->string("addressShipping")->nullable();
            $table->string("detailsShipping")->nullable();
            $table->string("selectShipping")->nullable();
            $table->string("priceShipping")->nullable();
            $table->integer("statusShipping")->default(0); // 0: no retirado producto 1: retirado producto 2: entregado
            $table->string('totalShipping');
            $table->integer("percentage");
            $table->String("nameCompanyPayments", 10);
            $table->String("date");
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

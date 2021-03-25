<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsVehicleToDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->string("model",50)->nullable();
            $table->string("mark",50)->nullable();
            $table->string("colorName",50)->nullable();
            $table->string("colorHex",10)->nullable();
            $table->string("licensePlate",20)->nullable();
            $table->float("balance", 20,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn("model");
            $table->dropColumn("mark");
            $table->dropColumn("colorName");
            $table->dropColumn("colorHex");
            $table->dropColumn("licensePlate");
            $table->dropColumn("balance");
        });
    }
}

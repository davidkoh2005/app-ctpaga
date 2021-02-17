<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusPaymentToPaidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paids', function (Blueprint $table) {
            $table->integer("statusPayment")->default(1); // 1 pendiente 2 confirmado
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paids', function (Blueprint $table) {
            $table->dropColumn('statusPayment');
        });
    }
}

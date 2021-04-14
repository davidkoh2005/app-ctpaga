<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToPaidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paids', function (Blueprint $table) {
            $table->String("state",20)->nullable();
            $table->String("municipalities",50)->nullable();
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
            $table->dropColumn("state");
            $table->dropColumn("municipalities");
        });
    }
}

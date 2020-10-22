<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('address');
            $table->string('phone', 20);
            $table->rememberToken();
            $table->string('rifCompany', 15)->nullable();
            $table->string('nameCompany')->nullable();
            $table->string('addressCompany')->nullable();
            $table->string('phoneCompany', 20)->nullable();
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
        Schema::dropIfExists('users');
    }
}

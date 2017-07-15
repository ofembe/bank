<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('accounts', function (Blueprint $table) {
          $table->increments('id');
          $table->bigInteger('balance')->default(0);
          $table->bigInteger('number')->default(0)->unique();
          $table->boolean('active')->default(true);
          $table->bigInteger('holder_id')->nullable();
          $table->bigInteger('overdraft_limit')->nullable();
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
        Schema::drop('accounts');
    }
}

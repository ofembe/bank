<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('holders', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->string('phone')->nullable();
          $table->string('email')->nullable();
          $table->bigInteger('code')->nullable();
          $table->boolean('active')->default(true);
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
        Schema::drop('holders');
    }
}

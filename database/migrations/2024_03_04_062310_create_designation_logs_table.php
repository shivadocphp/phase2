<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designation_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('designation_id')->unsigned();
            $table->string('designation');
            $table->bigInteger('user_id')->unsigned();
            $table->string('type');
            $table->timestamps();
            $table->foreign('designation_id')->references('id')->on('designations');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designation_logs');
    }
};

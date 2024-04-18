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
        Schema::create('employmentmode_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employmentmode_id');
            $table->string('employementmode');
            $table->bigInteger('user_id');
            $table->string('type');
            $table->timestamps();
            // $table->foreign('employmentmode_id')->references('id')->on('employementmodes');  
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employmentmode_logs');
    }
};

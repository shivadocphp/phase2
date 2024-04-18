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
        Schema::create('holiday_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('holiday_id');
            $table->date('holiday_date');
            $table->string('holiday_reason');
            $table->bigInteger('user_id');
            $table->string('type');
            $table->timestamps();
            // $table->foreign('holiday_id')->references('id')->on('holidays');  
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
        Schema::dropIfExists('holiday_logs');
    }
};

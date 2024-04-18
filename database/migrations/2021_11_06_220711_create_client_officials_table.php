<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientOfficialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_officials', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('service_type')->nullable();
            $table->date('date_empanelment')->nullable();
            $table->date('date_renewal')->nullable();
            $table->string('freezing_period')->nullable();
            $table->string('rehire_policy')->nullable();
            $table->string('profile_validity')->nullable();
            $table->date('callback_date')->nullable();
            $table->time('callback_time')->nullable();
            $table->integer('agreed1')->nullable();
            $table->integer('agreed2')->nullable();
            $table->integer('agreed3')->nullable();
            $table->integer('agreed4')->nullable();
            $table->integer('payment1')->nullable();
            $table->integer('payment2')->nullable();
            $table->integer('payment3')->nullable();
            $table->integer('payment4')->nullable();
            $table->integer('replacement1')->nullable();
            $table->integer('replacement2')->nullable();
            $table->integer('replacement3')->nullable();
            $table->integer('replacement4')->nullable();
            $table->string('agreement')->nullable();
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('client_basic_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_officials');
    }
}

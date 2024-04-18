<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('employee_targets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_id')->unsigned()->nullable();
            $table->string('emp_code');
            $table->integer('target');
            $table->integer('achieved');
            $table->integer('month');
            $table->integer('year');
            $table->string('comments');
            $table->timestamps();
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->foreign('emp_id')->references('id')->on('employee_personal_details')->onDelete('cascade');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_targets');
    }
}

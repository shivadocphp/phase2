<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePipDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('employee_pip_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_id')->unsigned();
            $table->string('emp_code')->unique();
            $table->date('first_review');
            $table->date('second_review')->nullable();
            $table->date('third_review')->nullable();
            $table->string('review_comment')->nullable();
            $table->bigInteger('added_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned();
            $table->timestamps();
            $table->foreign('emp_id')->references('id')->on('employee_personal_details')->onDelete('cascade');;
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');;

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_pip_details');
    }
}

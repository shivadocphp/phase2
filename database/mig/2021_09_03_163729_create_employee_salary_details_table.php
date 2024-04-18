<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSalaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('employee_salary_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_id')->unsigned();
            $table->string('emp_code')->unique();
            $table->double('fixed_basic');
            $table->double('fixed_hra');
            $table->double('fixed_conveyance');
            $table->integer('employer_pf');
            $table->integer('employer_esi');
            $table->integer('employee_pf')->nullable();
            $table->integer('employee_esi')->nullable();
            $table->integer('casual_leave_available')->nullable();
            $table->integer('monthly_target');
            $table->date('start_date')->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();
            $table->bigInteger('added_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned();
            $table->foreign('emp_id')->references('id')->on('employee_personal_details')->onDelete('cascade');;
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
        Schema::dropIfExists('employee_salary_details');
    }
}

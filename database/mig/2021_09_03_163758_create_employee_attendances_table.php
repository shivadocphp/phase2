<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::enableForeignKeyConstraints();
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code');
            $table->date('attendance_date');
            $table->time('login_time');
            $table->time('morning_break_in')->nullable();
            $table->time('morning_break_out')->nullable();
            $table->time('lunch_break_in')->nullable();
            $table->time('lunch_break_out')->nullable();
            $table->time('evening_break_in')->nullable();
            $table->time('evening_break_out')->nullable();
            $table->time('logout_time')->nullable();
            $table->time('total_working_hours')->nullable();
            $table->time('total_break_hours')->nullable();
            $table->bigInteger('shift_id')->unsigned()->nullable();
            $table->bigInteger('added_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('shift_id')->references('id')->on('employee_shifts')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_attendances');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeOfficialDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('employee_official_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_id')->unsigned()->nullable();
            $table->string('emp_code')->unique()->nullable();
            $table->foreignId('employementmode_id')->constrained();
            $table->bigInteger('client_id')->unsigned()->nullable();
            $table->integer('location')->nullable();

            $table->date('joining_date');
            $table->foreignId('department_id')->constrained();
            $table->foreignId('designation_id')->constrained();
            $table->string('official_emailid')->nullable();
            $table->string('esic_no')->nullable();
            $table->string('pf_no')->nullable();
            $table->string('uan_no')->nullable();
            $table->date('relieving_date')->nullable();
            $table->string('bgv');
            $table->string('comments')->nullable();
            $table->bigInteger('team_id')->unsigned()->nullable();
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('emp_id')->references('id')->on('employee_personal_details')->onDelete('cascade');;
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('client_id')->references('id')->on('client_basic_details')->onDelete('cascade');
            // $table->foreign('location')->references('id')->on('client_basic_details')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_official_details');
    }
}

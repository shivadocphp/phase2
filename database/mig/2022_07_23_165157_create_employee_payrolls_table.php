<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->string('category')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->integer('gross_sal')->nullable();
            $table->integer('basic')->nullable();
            $table->integer('hra')->nullable();
            $table->integer('fixed_gross')->nullable();
            $table->integer('epfo_employer')->nullable();
            $table->integer('epfo_employee')->nullable();
            $table->integer('esci_employer')->nullable();
            $table->integer('esci_employee')->nullable();
            $table->integer('ctc')->nullable();
            $table->integer('pt')->nullable();
            $table->integer('net_pay')->nullable();
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('emp_id')->references('id')->on('employee_personal_details')->onDelete('cascade');
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('employee_payrolls');
    }
}

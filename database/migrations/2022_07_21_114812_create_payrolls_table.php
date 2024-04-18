<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('gross_sal_limit')->nullable();
            $table->string('basic_perc')->nullable();
            $table->string('hra_perc')->nullable();
            $table->string('epfo_employer_perc')->nullable();
            $table->string('epfo_employee_perc')->nullable();
            $table->string('esic_employer_perc')->nullable();
            $table->string('esic_employee_perc')->nullable();
            $table->string('pt')->nullable();
            $table->string('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
}

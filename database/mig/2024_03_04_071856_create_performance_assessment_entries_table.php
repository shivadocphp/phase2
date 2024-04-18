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
        Schema::create('performance_assessment_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assessment_id')->unsigned();
            $table->bigInteger('emp_id')->unsigned();
            $table->integer('self_score')->unsigned()->nullable();
            $table->integer('manager_score')->nullable()->default(0);
            $table->string('financial_year'); 
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('emp_id')->references('id')->on('employee_personal_details');
            $table->foreign('assessment_id')->references('id')->on('performance_assessments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('performance_assessment_entries');
    }
};

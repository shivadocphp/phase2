<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePersonalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('employee_personal_details', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code')->nullable();
            $table->String('subtitle');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('gender');
            $table->date('dob')->nullable();
            $table->string('blood_group');
            $table->string('personal_emailID')->unique();
            $table->string('landline')->nullable();
            $table->string('mobile1');
            $table->string('mobile2')->nullable();
            $table->String('diff_abled');
            $table->String('aadhaar_no')->nullable();
            $table->String('pan_no')->nullable();
            $table->String('dl_no')->nullable();
            $table->date('dl_expiry_date')->nullable();
            $table->bigInteger('quali_level_id')->unsigned()->nullable();
            $table->bigInteger('quali_id')->unsigned()->nullable();
            $table->string('p_address1');
            $table->bigInteger('p_city_id')->unsigned()->nullable();
            $table->bigInteger('p_state_id')->unsigned()->nullable();
            $table->bigInteger('p_country_id')->unsigned()->nullable();
            $table->string('p_address_pincode')->nullable();
            $table->string('c_address1');
            $table->bigInteger('c_city_id')->unsigned()->nullable();
            $table->bigInteger('c_state_id')->unsigned()->nullable();
            $table->bigInteger('c_country_id')->unsigned()->nullable();
            $table->string('c_address_pincode')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_mobile')->nullable();
            $table->string('guardian_type')->nullable();
            $table->char('is_active');
            $table->integer('shift_id')->default(1);
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->timestamps();
           // $table->foreign('subtitle_id')->references('id')->on('subtitle')->onDelete('cascade');
          //  $table->foreign('blood_group_id')->references('id')->on('bloodgroups')->onDelete('cascade');
            $table->foreign('quali_level_id')->references('id')->on('qualificationlevels')->onDelete('cascade');
            $table->foreign('quali_id')->references('id')->on('qualifications')->onDelete('cascade');
            $table->foreign('c_city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('c_state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('c_country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('p_city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('p_state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('p_country_id')->references('id')->on('countries')->onDelete('cascade');
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
        Schema::dropIfExists('employee_personal_details');
    }
}

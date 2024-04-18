<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientBasicDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_basic_details', function (Blueprint $table) {
            $table->id();
            $table->string('client_code');
            $table->string('company_name');
            $table->string('company_emailID');
            $table->string('company_contact_no');
            $table->bigInteger('industry_type_id')->unsigned();
            $table->string('ceo');
            $table->string('ceo_contact');
            $table->string('ceo_emailID');
            $table->string('hr_spoc');
            $table->bigInteger('hr_desig')->unsigned();
            $table->string('fspoc');
            $table->bigInteger('fspoc_designation')->unsigned();
            $table->string('fspoc_contact');
            $table->string('fspoc_email');
            $table->string('client_status');
            $table->string('website')->nullable();
            $table->string('comments')->nullable();
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('industry_type_id')->references('id')->on('industrytypes')->onDelete('cascade');
            $table->foreign('hr_desig')->references('id')->on('designations')->onDelete('cascade');
            $table->foreign('fspoc_designation')->references('id')->on('designations')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_basic_details');
    }
}

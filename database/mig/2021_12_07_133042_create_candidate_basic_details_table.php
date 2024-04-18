<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateBasicDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_basic_details', function (Blueprint $table) {
            $table->id();
            $table->string('candidate_resume')->nullable();
            $table->string('candidate_name');
            $table->string('gender');
            $table->string('contact_no');
            $table->string('whatsapp_no');
            $table->string('candidate_email');
            $table->string('current_company')->nullable();
            $table->bigInteger('designation')->unsigned()->nullable();
            $table->integer('current_salary')->nullable();
            $table->integer('expected_salary')->nullable();
            $table->string('total_exp')->nullable();
            $table->integer('notice_period')->nullable();
            $table->char('duration')->nullable();
            $table->bigInteger('quali_level_id')->unsigned()->nullable();
            $table->bigInteger('quali_id')->unsigned()->nullable();
            $table->string('current_location')->nullable();
            $table->string('preferred_location')->nullable();
            $table->char('passport')->nullable();
            $table->string('employement_mode')->nullable();
            $table->string('pf_status')->nullable();
            $table->string('communication')->nullable();
            $table->string('preferred_shift')->nullable();
            $table->text('skills')->nullable();
            $table->string('status')->nullable(); 
            $table->text('comments')->nullable();
            $table->text('profile_source')->nullable();
            $table->bigInteger('added_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('quali_level_id')->references('id')->on('qualificationlevels')->onDelete('cascade');
            $table->foreign('quali_id')->references('id')->on('qualifications')->onDelete('cascade');
            $table->foreign('designation')->references('id')->on('designations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_basic_details');
    }
}

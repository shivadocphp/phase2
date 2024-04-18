<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candidate_id')->unsigned();
            $table->bigInteger('requirement_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->string('interview_mode')->nullable();
            $table->time('available_time')->nullable();
            
            $table->date('call_back_date')->nullable();
            $table->time('call_back_time')->nullable();
            $table->string('call_back_status')->nullable();
            $table->string('call_status')->nullable();
            $table->date('joining_date')->nullable();
            $table->integer('invoice_generation_limit')->default(0);
            $table->string('requirement_status')->nullable();
            $table->text('comments')->nullable();
            $table->bigInteger('added_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('candidate_basic_details')->onDelete('cascade');
            $table->foreign('requirement_id')->references('id')->on('client_requirements')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('client_basic_details')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_details');
    }
}

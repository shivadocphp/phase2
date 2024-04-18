<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_requirements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id')->unsigned();
            $table->integer('location');
            $table->integer('agreed');
            $table->bigInteger('position')->unsigned();
            $table->integer('total_position');
            $table->integer('min_years');
            $table->integer('max_years');
            $table->string('matriculation')->nullable();
            $table->string('plustwo')->nullable();
            $table->bigInteger('quali_level_id')->unsigned()->nullable();
            $table->bigInteger('quali_id')->unsigned()->nullable();
            $table->integer('salary_min');
            $table->integer('salary_max');
            $table->char('cab_facility')->nullable();
            $table->char('hiring_radius',10)->nullable();;
            $table->string('role_type')->nullable();
            $table->string('employement_type')->nullable();
            $table->string('domain')->nullable();
            $table->string('requirement_status');
            $table->text('skills');
            $table->text('jd');
            $table->text('targeted_companies')->nullable();
            $table->text('nonpatch_companies')->nullable();
            $table->integer('interview_rounds')->nullable();
            $table->date('open_till')->nullable();
            $table->integer('no_consultant')->nullable();
            $table->char('bond',1)->nullable();
            $table->integer('bond_years')->nullable();
            $table->string('tat')->nullable();
            $table->bigInteger('added_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('client_basic_details')->onDelete('cascade');
            $table->foreign('quali_level_id')->references('id')->on('qualificationlevels')->onDelete('cascade');
            $table->foreign('quali_id')->references('id')->on('qualifications')->onDelete('cascade');
        //    $table->foreign('location')->references('id')->on('client_addresses')->onDelete('cascade');
            $table->foreign('position')->references('id')->on('designations')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_requirements');
    }
}

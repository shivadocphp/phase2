<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeavesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::enableForeignKeyConstraints();
        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code')->nullable();
            $table->foreignId('leavetype_id')->constrained();
            $table->char('year',4);
            $table->integer('month');//January-1,February-2.......,December-12
            $table->integer('requested_days');
            $table->integer('requested_hours');
            $table->date('worked_on')->nullable();
            $table->date('required_from');
            $table->date('required_to');
            $table->string('reason');
            $table->integer('time_from')->nullable();
            $table->integer('time_to')->nullable();
            $table->float('approved_days')->nullable();
            $table->integer('approved_hours')->nullable();
            $table->date('approved_from')->nullable();
            $table->date('approved_to')->nullable();
            $table->text('comments')->nullable();
            $table->char('leave_status');
            $table->bigInteger('shift_id')->unsigned()->nullable();
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('shift_id')->references('id')->on('employee_shifts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('employee_leaves');
    }

}

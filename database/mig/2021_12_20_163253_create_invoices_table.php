<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('invoice_date');
            $table->string('invoice_type');
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('client_address_id')->unsigned();
            $table->integer('total_amount');
            $table->integer('sgst_amount')->nullable();
            $table->integer('cgst_amount')->nullable();
            $table->integer('igst_amount')->nullable();
            $table->integer('grand_total');
            $table->string('status')->nullable();
            $table->integer('paid_amount')->nullable();
            $table->integer('payment_date')->nullable();
            $table->integer('balance_amount')->nullable();
            $table->string('payment_history')->nullable();
            $table->bigInteger('added_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('client_basic_details')->onDelete('cascade');
            $table->foreign('client_address_id')->references('id')->on('client_addresses')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}

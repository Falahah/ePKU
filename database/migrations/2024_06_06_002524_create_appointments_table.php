<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('msID')->nullable();
            $table->string('selected_service_type'); 
            $table->unsignedBigInteger('selected_time_slot'); 
            $table->date('date');
            $table->unsignedBigInteger('feedback_rating')->nullable();
            $table->string('appointment_status')->default('upcoming');
            $table->timestamps();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('user_id')->references('userID')->on('users');
            $table->foreign('msID')->references('msID')->on('medical_staff');
            $table->foreign('selected_service_type')->references('service_type')->on('services');
            $table->foreign('selected_time_slot')->references('time_id')->on('time_slots');
            $table->foreign('feedback_rating')->references('fr_id')->on('feedback_rating');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}

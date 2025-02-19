<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalStaffTable extends Migration
{
    public function up()
    {
        Schema::create('medical_staff', function (Blueprint $table) {
            $table->id('msID')->nullable();
            $table->string('name');
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('position');
            $table->unsignedBigInteger('department_id')->nullable(); // Add department_id column
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->timestamps();

            // Define foreign key constraint for department_id
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
            $table->foreign('unit_id')->references('unit_id')->on('units')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_staff');
    }
}

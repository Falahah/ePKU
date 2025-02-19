<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalStaffTable extends Migration
{
    public function up()
    {
        Schema::create('medical_staff', function (Blueprint $table) {
            $table->id('msID');
            $table->string('name');
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('position');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->boolean('active')->default(true); // Add active column
            $table->timestamps();
    
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
            $table->foreign('unit_id')->references('unit_id')->on('units')->onDelete('cascade');
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('medical_staff');
    }
}

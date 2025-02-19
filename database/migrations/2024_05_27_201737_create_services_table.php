<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id('service_id');
            $table->string('service_type');
            $table->unsignedBigInteger('department_id'); // Add department_id column
            $table->time('sunday_opening')->nullable();
            $table->time('sunday_closing')->nullable();
            $table->time('monday_opening')->nullable();
            $table->time('monday_closing')->nullable();
            $table->time('tuesday_opening')->nullable();
            $table->time('tuesday_closing')->nullable();
            $table->time('wednesday_opening')->nullable();
            $table->time('wednesday_closing')->nullable();
            $table->time('thursday_opening')->nullable();
            $table->time('thursday_closing')->nullable();
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('department_id')->references('department_id')->on('departments');
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
}

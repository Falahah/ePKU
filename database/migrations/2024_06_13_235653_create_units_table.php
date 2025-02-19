<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id('unit_id');
            $table->string('name')->nullable(); 
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable(); 
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
}

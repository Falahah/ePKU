<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackRatingTable extends Migration
{
    public function up()
    {
        Schema::create('feedback_rating', function (Blueprint $table) {
            $table->id('fr_id');
            $table->unsignedBigInteger('related_appointment_id');
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable();
            $table->timestamps();
        });

        Schema::table('feedback_rating', function (Blueprint $table) {
            $table->foreign('related_appointment_id')->references('appointment_id')->on('appointments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback_rating');
    }
}

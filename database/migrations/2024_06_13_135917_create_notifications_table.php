<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Type of notification ('booked', 'cancelled', etc.)
            $table->text('data'); // Additional data for the notification
            $table->boolean('read')->default(false); // Read status
            $table->unsignedBigInteger('appointment_id')->nullable(); // Reference to the related appointment
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('appointment_id')->references('appointment_id')->on('appointments')->onDelete('cascade');
        });
    }

    public function down()
    {

        Schema::dropIfExists('notifications');
    }
}

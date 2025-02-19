<?php
// database/migrations/xxxx_xx_xx_create_time_slots_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeSlotsTable extends Migration
{
    public function up()
    {
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id('time_id');
            $table->time('start_time');
            $table->timestamps();
        });

        // Insert time slots from 8 am to 4 pm with 15 minutes intervals
        $this->insertTimeSlots();
    }

    public function down()
    {
        Schema::dropIfExists('time_slots');
    }

    private function insertTimeSlots()
    {
        $startTime = strtotime('08:00:00');
        $endTime = strtotime('19:30:00');
        $interval = 900; // 15 minutes in seconds

        while ($startTime <= $endTime) {
            $timeSlot = date('H:i:s', $startTime);
            DB::table('time_slots')->insert(['start_time' => $timeSlot]);
            $startTime += $interval;
        }
    }
}

<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        \Log::info('AppointmentCancelled event constructed for appointment ID: ' . $appointment->appointment_id);
        debug_backtrace(); // This will help trace where the event is being constructed
        $this->appointment = $appointment;
    }
}

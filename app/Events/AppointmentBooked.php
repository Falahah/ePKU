<?php
namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentBooked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        \Log::info('AppointmentBooked event constructed for appointment ID: ' . $appointment->appointment_id);
        $this->appointment = $appointment;
    }
}

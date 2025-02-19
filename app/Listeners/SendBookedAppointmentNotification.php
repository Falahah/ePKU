<?php
namespace App\Listeners;

use App\Events\AppointmentBooked;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class SendBookedAppointmentNotification
{
    public function handle(AppointmentBooked $event)
    {
        Log::info('Handling AppointmentBooked event for appointment ID: ' . $event->appointment->appointment_id);

        Notification::create([
            'type' => 'booked',
            'data' => 'A new appointment has been booked for ' . $event->appointment->selected_service_type . ' on ' . $event->appointment->date,
            'read' => false,
            'appointment_id' => $event->appointment->appointment_id,
        ]);
    }
}
